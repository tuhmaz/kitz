<?php

namespace App\Repositories;

use App\Models\SecurityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SecurityLogRepository
{
    protected SecurityLog $model;

    public function __construct(SecurityLog $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated security logs with filters.
     */
    public function getPaginatedLogs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user:id,name,email,profile_photo_path'])
            ->select([
                'id', 'ip_address', 'event_type', 'severity', 'created_at', 
                'user_id', 'is_resolved', 'country_code', 'city', 'description',
                'risk_score', 'route', 'resolved_at', 'resolved_by'
            ]);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get recent security logs.
     */
    public function getRecentLogs(int $limit = 10): Collection
    {
        return $this->model->with(['user:id,name,email,profile_photo_path'])
            ->select([
                'id', 'ip_address', 'event_type', 'severity', 'created_at', 
                'user_id', 'is_resolved', 'country_code', 'city', 'description',
                'risk_score'
            ])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get statistics by date range.
     */
    public function getStatsByDateRange(Carbon $startDate, Carbon $endDate): object
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_events,
                COUNT(CASE WHEN is_resolved = 1 THEN 1 END) as resolved_events,
                COUNT(CASE WHEN is_resolved = 0 THEN 1 END) as unresolved_events,
                AVG(risk_score) as avg_risk_score,
                MAX(risk_score) as max_risk_score,
                COUNT(CASE WHEN severity = ? THEN 1 END) as critical_events,
                COUNT(CASE WHEN severity = ? THEN 1 END) as danger_events,
                COUNT(CASE WHEN severity = ? THEN 1 END) as warning_events,
                AVG(CASE WHEN resolved_at IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, created_at, resolved_at) 
                    ELSE NULL END) as avg_resolution_time
            ', [
                SecurityLog::SEVERITY_LEVELS['CRITICAL'],
                SecurityLog::SEVERITY_LEVELS['DANGER'],
                SecurityLog::SEVERITY_LEVELS['WARNING']
            ])
            ->first();
    }

    /**
     * Get event distribution.
     */
    public function getEventDistribution(Carbon $startDate, Carbon $endDate): Collection
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->select('event_type', DB::raw('COUNT(*) as count'), DB::raw('AVG(risk_score) as avg_risk'))
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->get();
    }

    /**
     * Get events timeline.
     */
    public function getEventsTimeline(Carbon $startDate, Carbon $endDate): Collection
    {
        $criticalLevel = SecurityLog::SEVERITY_LEVELS['CRITICAL'];
        
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw("COUNT(CASE WHEN severity = '{$criticalLevel}' THEN 1 END) as critical_count"),
                DB::raw('AVG(risk_score) as avg_risk')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();
    }

    /**
     * Get top attacked routes.
     */
    public function getTopAttackedRoutes(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('route')
            ->select(
                'route',
                DB::raw('COUNT(*) as count'),
                DB::raw('MAX(created_at) as last_attack'),
                DB::raw('AVG(risk_score) as avg_risk'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_ips'),
                DB::raw('MAX(risk_score) as max_risk')
            )
            ->groupBy('route')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(function ($route) {
                $route->last_attack = Carbon::parse($route->last_attack);
                $route->risk_level = $this->getRiskLevel($route->max_risk);
                return $route;
            });
    }

    /**
     * Get geographic distribution.
     */
    public function getGeographicDistribution(Carbon $startDate, Carbon $endDate, int $limit = 10): Collection
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->select(
                'country_code',
                'city',
                DB::raw('COUNT(*) as events_count'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_ips'),
                DB::raw('AVG(risk_score) as avg_risk'),
                DB::raw('MAX(risk_score) as max_risk'),
                DB::raw('COUNT(DISTINCT event_type) as event_types_count')
            )
            ->groupBy('country_code', 'city')
            ->orderByDesc('events_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get blocked IPs with statistics.
     */
    public function getBlockedIpsWithStats(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('event_type', 'blocked_access')
            ->select([
                'ip_address',
                'country_code',
                'city',
                'attack_type',
                DB::raw('MAX(created_at) as last_attempt'),
                DB::raw('COUNT(*) as attempts_count'),
                DB::raw('MAX(risk_score) as max_risk_score'),
                DB::raw('AVG(risk_score) as avg_risk_score'),
                DB::raw('MIN(created_at) as first_attempt')
            ])
            ->groupBy('ip_address', 'country_code', 'city', 'attack_type')
            ->orderBy('last_attempt', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get trusted IPs with statistics.
     */
    public function getTrustedIpsWithStats(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('event_type', 'trusted_access')
            ->select([
                'ip_address',
                'country_code',
                'city',
                DB::raw('MAX(created_at) as last_access'),
                DB::raw('COUNT(*) as access_count'),
                DB::raw('MIN(created_at) as first_access'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users')
            ])
            ->groupBy('ip_address', 'country_code', 'city')
            ->orderByDesc('last_access')
            ->paginate($perPage);
    }

    /**
     * Get IP details with comprehensive statistics.
     */
    public function getIpDetails(string $ip): array
    {
        $logs = $this->model->where('ip_address', $ip)
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'first_seen' => $logs->last()?->created_at,
            'last_seen' => $logs->first()?->created_at,
            'total_events' => $logs->count(),
            'event_types' => $logs->groupBy('event_type')->map(function($group) { return $group->count(); }),
            'risk_scores' => [
                'current' => $logs->first()?->risk_score ?? 0,
                'average' => round($logs->avg('risk_score'), 2),
                'max' => $logs->max('risk_score'),
                'min' => $logs->min('risk_score')
            ],
            'locations' => $logs->groupBy('country_code')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'cities' => $group->pluck('city')->unique()->filter()->values()
                ];
            }),
            'severity_distribution' => $logs->groupBy('severity')->map(function($group) { return $group->count(); }),
            'resolution_stats' => [
                'resolved' => $logs->where('is_resolved', true)->count(),
                'pending' => $logs->where('is_resolved', false)->count(),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($logs->where('is_resolved', true))
            ],
            'routes' => $logs->pluck('route')->unique()->filter()->values(),
            'users' => $logs->pluck('user.name')->unique()->filter()->values(),
            'timeline' => $logs->groupBy(function($log) { return $log->created_at->format('Y-m-d'); })
                ->map(function($group) { return $group->count(); })
                ->sortKeys()
        ];

        return ['logs' => $logs, 'stats' => $stats];
    }

    /**
     * Apply filters to query.
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
        }

        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if (!empty($filters['ip'])) {
            $sanitizedIp = $this->sanitizeIpInput($filters['ip']);
            $query->where('ip_address', 'like', "%{$sanitizedIp}%");
        }

        if (isset($filters['is_resolved'])) {
            $query->where('is_resolved', $filters['is_resolved'] === 'true');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['risk_level'])) {
            $riskRange = $this->getRiskRange($filters['risk_level']);
            $query->whereBetween('risk_score', $riskRange);
        }

        if (!empty($filters['country_code'])) {
            $query->where('country_code', $filters['country_code']);
        }
    }

    /**
     * Sanitize IP input.
     */
    protected function sanitizeIpInput(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        
        return preg_replace('/[^0-9.]/', '', $ip);
    }

    /**
     * Get risk range for filtering.
     */
    protected function getRiskRange(string $level): array
    {
        return match($level) {
            'critical' => [90, 100],
            'high' => [75, 89],
            'medium' => [50, 74],
            'low' => [25, 49],
            'minimal' => [0, 24],
            default => [0, 100]
        };
    }

    /**
     * Get risk level from score.
     */
    protected function getRiskLevel(int $score): string
    {
        return match(true) {
            $score >= 90 => 'Critical',
            $score >= 75 => 'High',
            $score >= 50 => 'Medium',
            $score >= 25 => 'Low',
            default => 'Minimal'
        };
    }

    /**
     * Calculate average resolution time.
     */
    protected function calculateAvgResolutionTime(Collection $resolvedLogs): ?float
    {
        if ($resolvedLogs->isEmpty()) {
            return null;
        }

        $totalMinutes = $resolvedLogs->sum(function ($log) {
            return $log->resolved_at?->diffInMinutes($log->created_at) ?? 0;
        });

        return round($totalMinutes / $resolvedLogs->count(), 2);
    }
}
