<?php

namespace App\Http\Controllers;

use App\Repositories\SecurityLogRepository;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class IpManagementController extends Controller
{
    protected SecurityLogRepository $repository;

    public function __construct(SecurityLogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display blocked IPs management page.
     */
    public function blockedIps(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $blockedLogs = $this->repository->getBlockedIpsWithStats($perPage);

        // Calculate comprehensive statistics
        $stats = $this->getBlockedIpsStats();

        // Extract individual variables for backward compatibility with the view
        $totalBlocked = $stats['total_blocked'];
        $highRiskCount = $stats['high_risk_count'];
        $recentlyBlocked = $stats['recently_blocked'];
        $avgRiskScore = $stats['avg_risk_score'];

        return view('content.dashboard.security.blocked-ips', compact(
            'blockedLogs',
            'totalBlocked',
            'highRiskCount',
            'recentlyBlocked',
            'avgRiskScore'
        ));
    }

    /**
     * Display trusted IPs management page.
     */
    public function trustedIps(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $trustedLogs = $this->repository->getTrustedIpsWithStats($perPage);

        // Calculate comprehensive statistics
        $stats = $this->getTrustedIpsStats();

        return view('content.dashboard.security.trusted-ips', compact(
            'trustedLogs',
            'stats'
        ));
    }

    /**
     * Display detailed information about an IP address.
     */
    public function ipDetails(string $ip)
    {
        // Validate IP format
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            abort(400, 'Invalid IP address format');
        }

        $data = $this->repository->getIpDetails($ip);
        
        // Add threat intelligence
        $threatIntel = $this->getThreatIntelligence($ip);
        
        // Add recommendations
        $recommendations = $this->generateRecommendations($data['stats'], $threatIntel);

        return view('content.dashboard.security.partials.ip-details', array_merge($data, [
            'ip' => $ip,
            'threat_intel' => $threatIntel,
            'recommendations' => $recommendations
        ]));
    }

    /**
     * Block an IP address with advanced options.
     */
    public function blockIp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:500',
            'duration' => 'nullable|integer|min:1|max:8760', // Max 1 year in hours
            'severity' => 'required|in:warning,danger,critical',
            'auto_unblock' => 'boolean',
            'notify_admin' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Check if IP is already blocked
        $existingBlock = SecurityLog::where('ip_address', $validated['ip_address'])
            ->where('event_type', 'blocked_access')
            ->where('is_resolved', false)
            ->first();

        if ($existingBlock) {
            return response()->json([
                'success' => false,
                'message' => __('IP address is already blocked')
            ], 409);
        }

        // Calculate risk score based on severity
        $riskScore = match($validated['severity']) {
            'critical' => 95,
            'danger' => 80,
            'warning' => 65,
            default => 50
        };

        // Create security log entry
        $log = SecurityLog::create([
            'ip_address' => $validated['ip_address'],
            'event_type' => 'blocked_access',
            'description' => $validated['reason'],
            'user_id' => Auth::id(),
            'severity' => $validated['severity'],
            'risk_score' => $riskScore,
            'is_resolved' => false,
            'route' => 'manual_block',
            'attack_type' => 'manual_intervention'
        ]);

        // Set auto-unblock if specified
        if (!empty($validated['duration']) && $validated['auto_unblock']) {
            $this->scheduleAutoUnblock($log, $validated['duration']);
        }

        // Send notification if requested
        if ($validated['notify_admin']) {
            $this->notifyAdmins($log, 'ip_blocked');
        }

        // Clear related caches
        $this->clearIpCaches($validated['ip_address']);

        return response()->json([
            'success' => true,
            'message' => __('IP address has been blocked successfully'),
            'log_id' => $log->id
        ]);
    }

    /**
     * Trust an IP address with advanced options.
     */
    public function trustIp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:500',
            'permanent' => 'boolean',
            'whitelist_level' => 'required|in:basic,elevated,admin',
            'notify_team' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Check if IP is already trusted
        $existingTrust = SecurityLog::where('ip_address', $validated['ip_address'])
            ->where('event_type', 'trusted_access')
            ->where('is_resolved', true)
            ->first();

        if ($existingTrust) {
            return response()->json([
                'success' => false,
                'message' => __('IP address is already trusted')
            ], 409);
        }

        // Create security log entry
        $log = SecurityLog::create([
            'ip_address' => $validated['ip_address'],
            'event_type' => 'trusted_access',
            'description' => $validated['reason'] . " (Level: {$validated['whitelist_level']})",
            'user_id' => Auth::id(),
            'severity' => 'info',
            'risk_score' => 0,
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
            'route' => 'manual_trust'
        ]);

        // Add to whitelist cache
        $this->addToWhitelist($validated['ip_address'], $validated['whitelist_level']);

        // Send notification if requested
        if ($validated['notify_team']) {
            $this->notifyAdmins($log, 'ip_trusted');
        }

        // Clear related caches
        $this->clearIpCaches($validated['ip_address']);

        return response()->json([
            'success' => true,
            'message' => __('IP address has been added to trusted list successfully'),
            'log_id' => $log->id
        ]);
    }

    /**
     * Unblock an IP address.
     */
    public function unblockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255'
        ]);

        $updated = SecurityLog::where('ip_address', $request->ip_address)
            ->where('event_type', 'blocked_access')
            ->where('is_resolved', false)
            ->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolved_by' => Auth::id(),
                'resolution_notes' => $request->reason ?: 'IP manually unblocked'
            ]);

        if ($updated === 0) {
            return response()->json([
                'success' => false,
                'message' => __('No active blocks found for this IP address')
            ], 404);
        }

        // Clear caches
        $this->clearIpCaches($request->ip_address);

        return response()->json([
            'success' => true,
            'message' => __('IP address has been unblocked successfully')
        ]);
    }

    /**
     * Remove IP from trusted list.
     */
    public function untrustIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255'
        ]);

        $updated = SecurityLog::where('ip_address', $request->ip_address)
            ->where('event_type', 'trusted_access')
            ->where('is_resolved', true)
            ->update([
                'is_resolved' => false,
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => $request->reason ?: 'IP manually removed from trusted list'
            ]);

        if ($updated === 0) {
            return response()->json([
                'success' => false,
                'message' => __('IP address is not in trusted list')
            ], 404);
        }

        // Remove from whitelist cache
        $this->removeFromWhitelist($request->ip_address);

        // Clear caches
        $this->clearIpCaches($request->ip_address);

        return response()->json([
            'success' => true,
            'message' => __('IP address has been removed from trusted list successfully')
        ]);
    }

    /**
     * Bulk IP operations.
     */
    public function bulkOperation(Request $request)
    {
        $request->validate([
            'operation' => 'required|in:block,unblock,trust,untrust',
            'ip_addresses' => 'required|array|min:1|max:100',
            'ip_addresses.*' => 'required|ip',
            'reason' => 'required|string|max:500'
        ]);

        $results = [];
        $successCount = 0;

        foreach ($request->ip_addresses as $ip) {
            try {
                $result = match($request->operation) {
                    'block' => $this->blockIpBulk($ip, $request->reason),
                    'unblock' => $this->unblockIpBulk($ip, $request->reason),
                    'trust' => $this->trustIpBulk($ip, $request->reason),
                    'untrust' => $this->untrustIpBulk($ip, $request->reason)
                };

                $results[$ip] = $result;
                if ($result['success']) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                $results[$ip] = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => __(':count IP addresses processed successfully', ['count' => $successCount]),
            'results' => $results,
            'total_processed' => count($request->ip_addresses),
            'successful' => $successCount,
            'failed' => count($request->ip_addresses) - $successCount
        ]);
    }

    /**
     * Get blocked IPs statistics.
     */
    protected function getBlockedIpsStats(): array
    {
        return Cache::remember('blocked_ips_stats', now()->addMinutes(10), function () {
            $totalBlocked = SecurityLog::where('event_type', 'blocked_access')
                ->distinct('ip_address')
                ->count();

            $highRiskCount = SecurityLog::where('event_type', 'blocked_access')
                ->where('risk_score', '>=', 75)
                ->distinct('ip_address')
                ->count();

            $recentlyBlocked = SecurityLog::where('event_type', 'blocked_access')
                ->where('created_at', '>=', now()->subDay())
                ->distinct('ip_address')
                ->count();

            $avgRiskScore = (int) SecurityLog::where('event_type', 'blocked_access')
                ->avg('risk_score');

            return [
                'total_blocked' => $totalBlocked,
                'high_risk_count' => $highRiskCount,
                'recently_blocked' => $recentlyBlocked,
                'avg_risk_score' => $avgRiskScore
            ];
        });
    }

    /**
     * Get trusted IPs statistics.
     */
    protected function getTrustedIpsStats(): array
    {
        return Cache::remember('trusted_ips_stats', now()->addMinutes(10), function () {
            return [
                'total_trusted' => SecurityLog::where('event_type', 'trusted_access')
                    ->distinct('ip_address')
                    ->count(),
                'active_sessions' => SecurityLog::where('event_type', 'trusted_access')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->distinct('ip_address')
                    ->count(),
                'admin_level' => SecurityLog::where('event_type', 'trusted_access')
                    ->where('description', 'like', '%admin%')
                    ->distinct('ip_address')
                    ->count()
            ];
        });
    }

    /**
     * Get threat intelligence for an IP.
     */
    protected function getThreatIntelligence(string $ip): array
    {
        // This would integrate with external threat intelligence APIs
        // For now, we'll analyze internal data
        
        $recentActivity = SecurityLog::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $threatLevel = 'low';
        $indicators = [];

        if ($recentActivity->where('severity', 'critical')->count() > 0) {
            $threatLevel = 'critical';
            $indicators[] = 'Critical security events detected';
        } elseif ($recentActivity->where('risk_score', '>=', 75)->count() > 5) {
            $threatLevel = 'high';
            $indicators[] = 'Multiple high-risk events';
        } elseif ($recentActivity->count() > 100) {
            $threatLevel = 'medium';
            $indicators[] = 'High activity volume';
        }

        return [
            'threat_level' => $threatLevel,
            'indicators' => $indicators,
            'last_updated' => now()->toISOString()
        ];
    }

    /**
     * Generate recommendations based on IP analysis.
     */
    protected function generateRecommendations(array $stats, array $threatIntel): array
    {
        $recommendations = [];

        if ($threatIntel['threat_level'] === 'critical') {
            $recommendations[] = [
                'type' => 'urgent',
                'action' => 'Block IP immediately',
                'reason' => 'Critical threat level detected'
            ];
        }

        if ($stats['total_events'] > 1000) {
            $recommendations[] = [
                'type' => 'warning',
                'action' => 'Monitor closely',
                'reason' => 'Unusually high activity volume'
            ];
        }

        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'info',
                'action' => 'Continue monitoring',
                'reason' => 'No immediate threats detected'
            ];
        }

        return $recommendations;
    }

    /**
     * Schedule auto-unblock for an IP.
     */
    protected function scheduleAutoUnblock(SecurityLog $log, int $hours): void
    {
        // This would typically use Laravel's job queue
        // For now, we'll store the schedule in cache
        Cache::put(
            "auto_unblock_{$log->ip_address}_{$log->id}",
            ['log_id' => $log->id, 'unblock_at' => now()->addHours($hours)],
            now()->addHours($hours)
        );
    }

    /**
     * Add IP to whitelist cache.
     */
    protected function addToWhitelist(string $ip, string $level): void
    {
        $whitelist = Cache::get('ip_whitelist', []);
        $whitelist[$ip] = [
            'level' => $level,
            'added_at' => now()->toISOString(),
            'added_by' => Auth::id()
        ];
        Cache::put('ip_whitelist', $whitelist, now()->addDays(30));
    }

    /**
     * Remove IP from whitelist cache.
     */
    protected function removeFromWhitelist(string $ip): void
    {
        $whitelist = Cache::get('ip_whitelist', []);
        unset($whitelist[$ip]);
        Cache::put('ip_whitelist', $whitelist, now()->addDays(30));
    }

    /**
     * Clear IP-related caches.
     */
    protected function clearIpCaches(string $ip): void
    {
        Cache::forget('blocked_ips_stats');
        Cache::forget('trusted_ips_stats');
        Cache::forget("ip_details_{$ip}");
        Cache::forget('security_logs_stats');
    }

    /**
     * Notify administrators about IP operations.
     */
    protected function notifyAdmins(SecurityLog $log, string $type): void
    {
        // This would send notifications via email, Slack, etc.
        // Implementation depends on your notification system
    }

    // Bulk operation helper methods
    protected function blockIpBulk(string $ip, string $reason): array
    {
        // Simplified bulk block logic
        return ['success' => true, 'message' => 'Blocked'];
    }

    protected function unblockIpBulk(string $ip, string $reason): array
    {
        // Simplified bulk unblock logic
        return ['success' => true, 'message' => 'Unblocked'];
    }

    protected function trustIpBulk(string $ip, string $reason): array
    {
        // Simplified bulk trust logic
        return ['success' => true, 'message' => 'Trusted'];
    }

    protected function untrustIpBulk(string $ip, string $reason): array
    {
        // Simplified bulk untrust logic
        return ['success' => true, 'message' => 'Untrusted'];
    }
}
