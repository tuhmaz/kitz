<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityLogRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SecurityAnalyticsController extends Controller
{
    protected SecurityLogRepository $repository;

    public function __construct(SecurityLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display security analytics dashboard.
     */
    public function index(Request $request)
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

        // Get comprehensive analytics data
        $stats = $this->repository->getStatsByDateRange($startDate, $endDate);
        $securityScore = $this->calculateSecurityScore($stats);
        
        // Calculate score change from previous period
        $previousPeriodDays = $endDate->diffInDays($startDate);
        $previousStartDate = $startDate->copy()->subDays($previousPeriodDays);
        $previousStats = $this->repository->getStatsByDateRange($previousStartDate, $startDate);
        $previousScore = $this->calculateSecurityScore($previousStats);
        $scoreChange = $securityScore - $previousScore;

        // Get detailed analytics
        $eventDistribution = $this->repository->getEventDistribution($startDate, $endDate);
        $eventsTimeline = $this->repository->getEventsTimeline($startDate, $endDate);
        $topAttackedRoutes = $this->repository->getTopAttackedRoutes($startDate, $endDate, 10);
        $geoDistribution = $this->repository->getGeographicDistribution($startDate, $endDate, 10);

        // Calculate additional metrics
        $avgResponseTime = $stats->avg_resolution_time ? round($stats->avg_resolution_time) : 0;
        $resolutionRate = $stats->total_events > 0 
            ? round(($stats->resolved_events / $stats->total_events) * 100)
            : 100;
        $pendingIssues = $stats->unresolved_events;

        // Risk distribution analysis
        $riskDistribution = $this->calculateRiskDistribution($stats);
        
        // Threat trends
        $threatTrends = $this->analyzeThreatTrends($eventsTimeline);
        
        // Response time trend (required by the view)
        $responseTimeTrend = $this->getResponseTimeTrend($startDate, $endDate);

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
            'riskDistribution',
            'threatTrends',
            'responseTimeTrend',
            'range',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get real-time security metrics via AJAX.
     */
    public function realTimeMetrics(Request $request)
    {
        $minutes = $request->get('minutes', 60);
        $startTime = now()->subMinutes($minutes);
        
        $metrics = $this->repository->getStatsByDateRange($startTime, now());
        
        return response()->json([
            'total_events' => $metrics->total_events,
            'critical_events' => $metrics->critical_events,
            'avg_risk_score' => round($metrics->avg_risk_score ?? 0, 2),
            'resolution_rate' => $metrics->total_events > 0 
                ? round(($metrics->resolved_events / $metrics->total_events) * 100, 2)
                : 100,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get threat intelligence data.
     */
    public function threatIntelligence(Request $request)
    {
        $hours = $request->get('hours', 24);
        $startTime = now()->subHours($hours);
        
        $topAttackedRoutes = $this->repository->getTopAttackedRoutes($startTime, now(), 5);
        $geoDistribution = $this->repository->getGeographicDistribution($startTime, now(), 5);
        $eventDistribution = $this->repository->getEventDistribution($startTime, now());

        return response()->json([
            'top_attacked_routes' => $topAttackedRoutes,
            'geographic_threats' => $geoDistribution,
            'attack_patterns' => $eventDistribution,
            'generated_at' => now()->toISOString()
        ]);
    }

    /**
     * Calculate security score based on various metrics.
     */
    protected function calculateSecurityScore(object $stats): int
    {
        if ($stats->total_events === 0) {
            return 100;
        }

        // Risk score component (30%)
        $avgRisk = $stats->avg_risk_score ?? 0;
        $riskScore = max(0, 100 - ($avgRisk * 10));
        
        // Resolution rate component (40%)
        $resolutionRate = ($stats->resolved_events / $stats->total_events) * 100;
        
        // Response time component (30%)
        $avgResolutionTime = $stats->avg_resolution_time ?? 0;
        $responseScore = max(0, 100 - (($avgResolutionTime / 120) * 100));

        return round(
            ($riskScore * 0.3) +
            ($resolutionRate * 0.4) +
            ($responseScore * 0.3)
        );
    }

    /**
     * Calculate risk distribution.
     */
    protected function calculateRiskDistribution(object $stats): array
    {
        return [
            'critical' => $stats->critical_events,
            'high' => $stats->danger_events,
            'medium' => $stats->warning_events,
            'low' => max(0, $stats->total_events - $stats->critical_events - $stats->danger_events - $stats->warning_events)
        ];
    }

    /**
     * Analyze threat trends.
     */
    protected function analyzeThreatTrends($timeline): array
    {
        if ($timeline->isEmpty()) {
            return ['trend' => 'stable', 'change_percentage' => 0];
        }

        $recentData = $timeline->take(-7); // Last 7 days
        $olderData = $timeline->take(7);   // First 7 days

        $recentAvg = $recentData->avg('count');
        $olderAvg = $olderData->avg('count');

        if ($olderAvg == 0) {
            return ['trend' => 'stable', 'change_percentage' => 0];
        }

        $changePercentage = (($recentAvg - $olderAvg) / $olderAvg) * 100;

        $trend = match(true) {
            $changePercentage > 20 => 'increasing',
            $changePercentage < -20 => 'decreasing',
            default => 'stable'
        };

        return [
            'trend' => $trend,
            'change_percentage' => round($changePercentage, 2)
        ];
    }

    /**
     * Get response time trend data.
     */
    protected function getResponseTimeTrend(Carbon $startDate, Carbon $endDate)
    {
        // Use the existing eventsTimeline data but format it for response time trend
        $eventsTimeline = $this->repository->getEventsTimeline($startDate, $endDate);
        
        // Transform the timeline data to include response time information
        return $eventsTimeline->map(function ($item) {
            return (object) [
                'date' => $item->date,
                'avg_time' => $item->avg_risk ?? 0, // Use avg_risk as a proxy for response complexity
                'avg_risk' => $item->avg_risk ?? 0
            ];
        });
    }
}
