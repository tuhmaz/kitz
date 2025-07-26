<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use App\Services\SecurityLogService;
use App\Repositories\SecurityLogRepository;
use App\Events\SecurityThreatDetected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SecurityLogController extends Controller
{
    protected $eventTypes = [
        'login_failed',
        'suspicious_activity',
        'blocked_access',
        'unauthorized_access',
        'password_reset',
        'account_locked',
        'permission_change'
    ];

    protected $severityLevels = [
        'info',
        'warning',
        'danger',
        'critical'
    ];

    protected SecurityLogService $securityLogService;
    protected SecurityLogRepository $repository;

    public function __construct(
        SecurityLogService $securityLogService,
        SecurityLogRepository $repository
    ) {
        $this->securityLogService = $securityLogService;
        $this->repository = $repository;
    }

    /**
     * عرض لوحة تحليل سجلات الأمان.
     */
    public function index()
    {
        $stats = $this->securityLogService->getQuickStats();
        $recentLogs = $this->repository->getRecentLogs(10);

        return view('content.dashboard.security.index', compact('stats', 'recentLogs'));
    }

    /**
     * Display the security logs list.
     */
    public function logs(Request $request)
    {
        $filters = $request->only([
            'event_type', 'severity', 'ip', 'is_resolved', 
            'date_from', 'date_to', 'risk_level', 'country_code'
        ]);

        $logs = $this->repository->getPaginatedLogs($filters, $request->get('per_page', 15));

        return view('content.dashboard.security.logs', [
            'logs' => $logs,
            'eventTypes' => $this->eventTypes,
            'severityLevels' => $this->severityLevels
        ]);
    }

    /**
     * Display security analytics.
     */
    public function analytics(Request $request)
    {
        // Calculate date range
        $range = $request->get('range', 'week');
        $startDate = match ($range) {
            'today' => now()->startOfDay(),
            'week' => now()->subDays(7)->startOfDay(),
            'month' => now()->subDays(30)->startOfDay(),
            'custom' => Carbon::parse($request->get('start_date'))->startOfDay(),
            default => now()->subDays(7)->startOfDay(),
        };
        $endDate = $range === 'custom' 
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : now();

        // Calculate security score based on risk scores and resolution times
        $stats = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                AVG(risk_score) as avg_risk,
                COUNT(*) as total_events,
                COUNT(CASE WHEN is_resolved = 1 THEN 1 END) as resolved_events,
                AVG(CASE WHEN resolved_at IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, created_at, resolved_at) 
                    ELSE NULL END) as avg_resolution_time
            ')
            ->first();

        $securityScore = $this->calculateSecurityScore(
            $stats->avg_risk ?? 0,
            $stats->total_events,
            $stats->resolved_events,
            $stats->avg_resolution_time ?? 0
        );

        // Calculate score change
        $previousStats = SecurityLog::whereBetween('created_at', [$startDate->copy()->subDays($endDate->diffInDays($startDate)), $startDate])
            ->selectRaw('
                AVG(risk_score) as avg_risk,
                COUNT(*) as total_events,
                COUNT(CASE WHEN is_resolved = 1 THEN 1 END) as resolved_events,
                AVG(CASE WHEN resolved_at IS NOT NULL 
                    THEN TIMESTAMPDIFF(MINUTE, created_at, resolved_at) 
                    ELSE NULL END) as avg_resolution_time
            ')
            ->first();

        $previousScore = $this->calculateSecurityScore(
            $previousStats->avg_risk ?? 0,
            $previousStats->total_events,
            $previousStats->resolved_events,
            $previousStats->avg_resolution_time ?? 0
        );

        $scoreChange = $securityScore - $previousScore;

        // Get event distribution
        $eventDistribution = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->get();

        // Get events timeline
        $eventsTimeline = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // Get top attacked routes with risk assessment
        $topAttackedRoutes = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('route')
            ->select(
                'route',
                DB::raw('count(*) as count'),
                DB::raw('MAX(created_at) as last_attack'),
                DB::raw('AVG(risk_score) as avg_risk'),
                DB::raw('MAX(occurrence_count) as max_occurrences')
            )
            ->groupBy('route')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($route) {
                $route->last_attack = Carbon::parse($route->last_attack);
                return $route;
            });

        // Get geographic distribution with attack patterns
        $geoDistribution = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country_code')
            ->select(
                'country_code',
                'city',
                DB::raw('count(*) as events_count'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_ips'),
                DB::raw('AVG(risk_score) as avg_risk'),
                DB::raw('COUNT(DISTINCT attack_type) as attack_types_count')
            )
            ->groupBy('country_code', 'city')
            ->orderByDesc('events_count')
            ->limit(5)
            ->get();

        // Get response time analysis
        $avgResponseTime = $stats->avg_resolution_time ? round($stats->avg_resolution_time) : 0;
        $resolutionRate = $stats->total_events > 0 
            ? round(($stats->resolved_events / $stats->total_events) * 100)
            : 100;
        $pendingIssues = $stats->total_events - $stats->resolved_events;

        // Get response time trend
        $responseTimeTrend = SecurityLog::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('resolved_at')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_time'),
                DB::raw('AVG(risk_score) as avg_risk')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        return view('content.dashboard.security.analytics', compact(
            'securityScore',
            'scoreChange',
            'eventDistribution',
            'eventsTimeline',
            'topAttackedRoutes',
            'geoDistribution',
            'avgResponseTime',
            'resolutionRate',
            'pendingIssues',
            'responseTimeTrend'
        ));
    }

    /**
     * Mark a security log as resolved.
     */
    public function resolve(SecurityLog $log, Request $request)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        $log->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
            'resolution_notes' => $request->notes
        ]);

        // Clear related caches
        Cache::forget('security_logs_stats');
        Cache::forget('blocked_ips_stats');

        return response()->json([
            'success' => true,
            'message' => __('Security log marked as resolved successfully.')
        ]);
    }

    /**
     * Delete a security log.
     */
    public function destroy(SecurityLog $log)
    {
        $log->delete();

        return back()->with('success', __('Security log deleted successfully.'));
    }

    /**
     * Export security logs to CSV.
     */
    public function export(Request $request)
    {
        // Add rate limiting check
        if (!$this->checkExportRateLimit()) {
            abort(429, 'Too many export requests. Please try again later.');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="security-logs.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $logs = SecurityLog::with('user:id,name,email')
            ->when($request->filled('event_type'), function ($q) use ($request) {
                return $q->where('event_type', $request->event_type);
            })
            ->when($request->filled('severity'), function ($q) use ($request) {
                return $q->where('severity', $request->severity);
            })
            ->when($request->filled('is_resolved'), function ($q) use ($request) {
                return $q->where('is_resolved', $request->is_resolved === 'true');
            })
            ->latest()
            ->limit(10000) // Limit export to prevent memory issues
            ->get();

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID',
                'Time',
                'IP Address',
                'Event Type',
                'Description',
                'User',
                'Route',
                'Severity',
                'Status',
                'Risk Score',
                'Country',
                'City'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, $this->sanitizeExportData($log));
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Sanitize data for export to prevent sensitive data leakage.
     */
    protected function sanitizeExportData($log): array
    {
        return [
            $log->id,
            $log->created_at->format('Y-m-d H:i:s'),
            $this->maskSensitiveIp($log->ip_address),
            $log->event_type,
            $this->sanitizeDescription($log->description),
            $log->user ? $log->user->name : 'System',
            $this->sanitizeRoute($log->route),
            $log->severity,
            $log->is_resolved ? 'Resolved' : 'Pending',
            $log->risk_score,
            $log->country_code,
            $log->city
        ];
    }

    /**
     * Mask sensitive parts of IP address.
     */
    protected function maskSensitiveIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            return $parts[0] . '.' . $parts[1] . '.***.' . $parts[3];
        }
        return substr($ip, 0, 8) . '***';
    }

    /**
     * Sanitize description to remove sensitive information.
     */
    protected function sanitizeDescription(string $description): string
    {
        // Remove potential passwords, tokens, or sensitive data
        $patterns = [
            '/password[=:]\s*[^\s&]+/i',
            '/token[=:]\s*[^\s&]+/i',
            '/api_key[=:]\s*[^\s&]+/i',
            '/secret[=:]\s*[^\s&]+/i',
        ];
        
        return preg_replace($patterns, '[REDACTED]', $description);
    }

    /**
     * Sanitize route information.
     */
    protected function sanitizeRoute(?string $route): string
    {
        if (!$route) return '';
        
        // Remove sensitive parameters from routes
        return preg_replace('/\/\d+/', '/***', $route);
    }

    /**
     * Check export rate limit.
     */
    protected function checkExportRateLimit(): bool
    {
        $key = 'export_rate_limit:' . Auth::id();
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 5) { // Max 5 exports per hour
            return false;
        }
        
        Cache::put($key, $attempts + 1, now()->addHour());
        return true;
    }

    /**
     * Display the list of blocked IP addresses.
     */
    public function blockedIps(Request $request)
    {
        // Redirect to new IP Management Controller
        return redirect()->route('dashboard.security.ip-management.blocked-ips');
    }

    /**
     * Display trusted IPs list.
     */
    public function trustedIps(Request $request)
    {
        // Redirect to new IP Management Controller
        return redirect()->route('dashboard.security.ip-management.trusted-ips');
    }

    /**
     * Block an IP address with event triggering.
     */
    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:500',
            'severity' => 'in:warning,danger,critical'
        ]);

        $severity = $request->get('severity', 'warning');
        $riskScore = match($severity) {
            'critical' => 95,
            'danger' => 80,
            'warning' => 65,
            default => 50
        };

        // Create a security log entry for blocking
        $log = SecurityLog::create([
            'ip_address' => $request->ip_address,
            'event_type' => 'blocked_access',
            'description' => $request->reason,
            'user_id' => Auth::id(),
            'severity' => $severity,
            'risk_score' => $riskScore,
            'is_resolved' => false,
            'route' => 'manual_block'
        ]);

        // Trigger security threat event
        if ($riskScore >= 75) {
            event(new SecurityThreatDetected($log, $riskScore, 'high', [
                'manual_block' => true,
                'blocked_by' => Auth::user()->name
            ]));
        }

        // Clear caches
        Cache::forget('security_logs_stats');
        Cache::forget('blocked_ips_stats');

        return response()->json([
            'success' => true,
            'message' => __('IP address has been blocked successfully'),
            'log_id' => $log->id
        ]);
    }

    /**
     * Trust an IP address.
     */
    public function trustIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:255'
        ]);

        // Create a security log entry for trusting
        SecurityLog::create([
            'ip_address' => $request->ip_address,
            'event_type' => 'trusted_access',
            'description' => $request->reason,
            'user_id' => Auth::id(),
            'severity' => 'info',
            'risk_score' => 0, // Trusted IPs have 0 risk score
            'is_resolved' => true
        ]);

        return response()->json([
            'message' => __('IP address has been added to trusted list successfully'),
            'status' => 'success'
        ]);
    }

    /**
     * Remove IP from blocked list.
     */
    public function unblockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip'
        ]);

        // Update all blocked entries for this IP
        SecurityLog::where('ip_address', $request->ip_address)
            ->where('event_type', 'blocked_access')
            ->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolved_by' => Auth::id(),
                'resolution_notes' => 'IP manually unblocked'
            ]);

        return response()->json([
            'message' => __('IP address has been unblocked successfully'),
            'status' => 'success'
        ]);
    }

    /**
     * Remove IP from trusted list.
     */
    public function untrustIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip'
        ]);

        // Update all trusted entries for this IP
        SecurityLog::where('ip_address', $request->ip_address)
            ->where('event_type', 'trusted_access')
            ->update([
                'is_resolved' => false,
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => 'IP manually removed from trusted list'
            ]);

        return response()->json([
            'message' => __('IP address has been removed from trusted list successfully'),
            'status' => 'success'
        ]);
    }

    /**
     * Display detailed information about an IP address.
     */
    public function ipDetails($ip)
    {
        // Get all logs for this IP
        $logs = SecurityLog::where('ip_address', $ip)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get summary statistics
        $stats = [
            'first_seen' => $logs->last()->created_at ?? null,
            'last_seen' => $logs->first()->created_at ?? null,
            'total_events' => $logs->count(),
            'event_types' => $logs->groupBy('event_type')
                ->map(fn($group) => $group->count()),
            'risk_scores' => [
                'current' => $logs->first()->risk_score ?? 0,
                'average' => $logs->avg('risk_score'),
                'max' => $logs->max('risk_score')
            ],
            'locations' => $logs->groupBy('country_code')
                ->map(fn($group) => [
                    'count' => $group->count(),
                    'cities' => $group->pluck('city')->unique()->filter()->values()
                ]),
            'user_agents' => $logs->pluck('user_agent')->unique()->filter()->values(),
            'routes' => $logs->pluck('route')->unique()->filter()->values(),
            'users' => $logs->pluck('user.name')->unique()->filter()->values()
        ];

        return view('content.dashboard.security.partials.ip-details', compact('logs', 'stats', 'ip'));
    }

    /**
     * Sanitize IP input to prevent SQL injection.
     */
    protected function sanitizeIpInput(string $ip): string
    {
        // First try to validate as a complete IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        
        // If not a complete IP, sanitize for partial matching
        return preg_replace('/[^0-9.]/', '', $ip);
    }

    /**
     * Calculate security score based on various metrics.
     */
    protected function calculateSecurityScore(
        float $avgRisk, 
        int $totalEvents, 
        int $resolvedEvents, 
        float $avgResolutionTime
    ): int {
        if ($totalEvents === 0) {
            return 100;
        }

        // Risk score component (30%)
        $riskScore = max(0, 100 - ($avgRisk * 10));
        
        // Resolution rate component (40%)
        $resolutionRate = ($resolvedEvents / $totalEvents) * 100;
        
        // Response time component (30%)
        $responseScore = max(0, 100 - (($avgResolutionTime / 120) * 100));

        return round(
            ($riskScore * 0.3) +
            ($resolutionRate * 0.4) +
            ($responseScore * 0.3)
        );
    }

    /**
     * Get color for event type.
     */
    protected function getEventTypeColor($eventType)
    {
        return match ($eventType) {
            'login_failed' => 'danger',
            'suspicious_activity' => 'warning',
            'blocked_access' => 'danger',
            'unauthorized_access' => 'danger',
            'password_reset' => 'info',
            'account_locked' => 'warning',
            'permission_change' => 'info',
            default => 'secondary'
        };
    }
}
