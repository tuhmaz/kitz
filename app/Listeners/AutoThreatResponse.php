<?php

namespace App\Listeners;

use App\Events\SecurityThreatDetected;
use App\Models\SecurityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AutoThreatResponse implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SecurityThreatDetected $event): void
    {
        try {
            $this->analyzeAndRespond($event);
        } catch (\Exception $e) {
            Log::error('Auto threat response failed', [
                'log_id' => $event->log->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Analyze threat and take appropriate action.
     */
    protected function analyzeAndRespond(SecurityThreatDetected $event): void
    {
        $log = $event->log;
        $riskScore = $event->riskScore;

        // Critical threats - immediate action
        if ($riskScore >= 90) {
            $this->handleCriticalThreat($log);
        }
        // High-risk threats - enhanced monitoring
        elseif ($riskScore >= 75) {
            $this->handleHighRiskThreat($log);
        }
        // Medium-risk threats - pattern analysis
        elseif ($riskScore >= 50) {
            $this->handleMediumRiskThreat($log);
        }

        // Update threat intelligence
        $this->updateThreatIntelligence($log, $riskScore);
    }

    /**
     * Handle critical threats with immediate blocking.
     */
    protected function handleCriticalThreat(SecurityLog $log): void
    {
        // Auto-block IP for critical threats
        $this->autoBlockIp($log->ip_address, 'Auto-blocked due to critical threat detection', 24);

        // Mark related logs as high priority
        SecurityLog::where('ip_address', $log->ip_address)
            ->where('created_at', '>=', now()->subHours(24))
            ->update(['severity' => 'critical']);

        Log::warning('Critical threat auto-blocked', [
            'ip_address' => $log->ip_address,
            'log_id' => $log->id,
            'risk_score' => $log->risk_score
        ]);
    }

    /**
     * Handle high-risk threats with enhanced monitoring.
     */
    protected function handleHighRiskThreat(SecurityLog $log): void
    {
        // Add to watch list
        $this->addToWatchList($log->ip_address, 'high_risk', 12);

        // Check for pattern escalation
        $recentThreats = SecurityLog::where('ip_address', $log->ip_address)
            ->where('risk_score', '>=', 75)
            ->where('created_at', '>=', now()->subHours(6))
            ->count();

        if ($recentThreats >= 3) {
            $this->escalateThreat($log);
        }

        Log::info('High-risk threat detected and monitored', [
            'ip_address' => $log->ip_address,
            'log_id' => $log->id,
            'recent_threats' => $recentThreats
        ]);
    }

    /**
     * Handle medium-risk threats with pattern analysis.
     */
    protected function handleMediumRiskThreat(SecurityLog $log): void
    {
        // Analyze patterns for this IP
        $patterns = $this->analyzeIpPatterns($log->ip_address);

        if ($patterns['is_suspicious']) {
            $this->addToWatchList($log->ip_address, 'medium_risk', 6);
            
            Log::info('Medium-risk threat added to watch list', [
                'ip_address' => $log->ip_address,
                'log_id' => $log->id,
                'patterns' => $patterns
            ]);
        }
    }

    /**
     * Auto-block an IP address.
     */
    protected function autoBlockIp(string $ip, string $reason, int $hours): void
    {
        SecurityLog::create([
            'ip_address' => $ip,
            'event_type' => 'blocked_access',
            'description' => $reason,
            'user_id' => null, // System action
            'severity' => 'critical',
            'risk_score' => 95,
            'is_resolved' => false,
            'route' => 'auto_block',
            'attack_type' => 'auto_response'
        ]);

        // Add to blocked IPs cache
        $blockedIps = Cache::get('auto_blocked_ips', []);
        $blockedIps[$ip] = [
            'blocked_at' => now()->toISOString(),
            'reason' => $reason,
            'expires_at' => now()->addHours($hours)->toISOString()
        ];
        Cache::put('auto_blocked_ips', $blockedIps, now()->addHours($hours));
    }

    /**
     * Add IP to watch list.
     */
    protected function addToWatchList(string $ip, string $level, int $hours): void
    {
        $watchList = Cache::get('security_watch_list', []);
        $watchList[$ip] = [
            'level' => $level,
            'added_at' => now()->toISOString(),
            'expires_at' => now()->addHours($hours)->toISOString(),
            'alert_count' => ($watchList[$ip]['alert_count'] ?? 0) + 1
        ];
        Cache::put('security_watch_list', $watchList, now()->addHours($hours));
    }

    /**
     * Escalate threat to critical level.
     */
    protected function escalateThreat(SecurityLog $log): void
    {
        // Update log severity
        $log->update(['severity' => 'critical']);

        // Auto-block the IP
        $this->autoBlockIp(
            $log->ip_address, 
            'Auto-escalated from high-risk to critical threat', 
            48
        );

        Log::warning('Threat escalated to critical level', [
            'ip_address' => $log->ip_address,
            'log_id' => $log->id
        ]);
    }

    /**
     * Analyze IP patterns for suspicious behavior.
     */
    protected function analyzeIpPatterns(string $ip): array
    {
        $recentLogs = SecurityLog::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHours(24))
            ->get();

        $patterns = [
            'is_suspicious' => false,
            'request_frequency' => $recentLogs->count(),
            'unique_routes' => $recentLogs->pluck('route')->unique()->count(),
            'failed_attempts' => $recentLogs->where('event_type', 'login_failed')->count(),
            'time_spread' => $this->calculateTimeSpread($recentLogs),
            'risk_escalation' => $this->detectRiskEscalation($recentLogs)
        ];

        // Determine if suspicious
        $patterns['is_suspicious'] = 
            $patterns['request_frequency'] > 100 ||
            $patterns['failed_attempts'] > 10 ||
            $patterns['unique_routes'] > 20 ||
            $patterns['risk_escalation'];

        return $patterns;
    }

    /**
     * Calculate time spread of requests.
     */
    protected function calculateTimeSpread($logs): float
    {
        if ($logs->count() < 2) {
            return 0;
        }

        $timestamps = $logs->pluck('created_at')->map(fn($date) => $date->timestamp)->sort();
        $intervals = [];

        for ($i = 1; $i < $timestamps->count(); $i++) {
            $intervals[] = $timestamps[$i] - $timestamps[$i - 1];
        }

        return collect($intervals)->avg();
    }

    /**
     * Detect risk escalation pattern.
     */
    protected function detectRiskEscalation($logs): bool
    {
        $riskScores = $logs->sortBy('created_at')->pluck('risk_score')->values();
        
        if ($riskScores->count() < 3) {
            return false;
        }

        // Check if risk scores are generally increasing
        $increases = 0;
        for ($i = 1; $i < $riskScores->count(); $i++) {
            if ($riskScores[$i] > $riskScores[$i - 1]) {
                $increases++;
            }
        }

        return ($increases / ($riskScores->count() - 1)) > 0.6; // 60% increasing trend
    }

    /**
     * Update threat intelligence data.
     */
    protected function updateThreatIntelligence(SecurityLog $log, int $riskScore): void
    {
        $threatIntel = Cache::get('threat_intelligence', []);
        
        $key = $log->ip_address;
        $threatIntel[$key] = [
            'ip_address' => $log->ip_address,
            'last_seen' => now()->toISOString(),
            'max_risk_score' => max($riskScore, $threatIntel[$key]['max_risk_score'] ?? 0),
            'total_events' => ($threatIntel[$key]['total_events'] ?? 0) + 1,
            'threat_types' => array_unique(array_merge(
                $threatIntel[$key]['threat_types'] ?? [],
                [$log->event_type]
            )),
            'countries' => array_unique(array_merge(
                $threatIntel[$key]['countries'] ?? [],
                $log->country_code ? [$log->country_code] : []
            ))
        ];

        Cache::put('threat_intelligence', $threatIntel, now()->addDays(30));
    }
}
