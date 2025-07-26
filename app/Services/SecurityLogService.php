<?php

namespace App\Services;

use App\Models\SecurityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SecurityLogService
{
    /**
     * تحليل النشاط المشبوه وحساب درجة الخطورة.
     *
     * @param SecurityLog $log
     * @return int
     */
    public function analyzeSuspiciousActivity(SecurityLog $log): int
    {
        $score = $this->calculateIpRiskScore($log);
        $score += $this->calculateFailedLoginRiskScore($log);
        $score += $this->calculateSensitiveRouteRiskScore($log);

        $log->risk_score = min($score, 100);
        $log->save();

        return $score;
    }

    /**
     * حساب درجة الخطورة بناءً على تكرار IP.
     *
     * @param SecurityLog $log
     * @return int
     */
    protected function calculateIpRiskScore(SecurityLog $log): int
    {
        $ipCount = SecurityLog::where('ip_address', $log->ip_address)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        $score = 0;
        if ($ipCount > 10) $score += 30;
        if ($ipCount > 50) $score += 50;

        return $score;
    }

    /**
     * حساب درجة الخطورة بناءً على محاولات تسجيل الدخول الفاشلة.
     *
     * @param SecurityLog $log
     * @return int
     */
    protected function calculateFailedLoginRiskScore(SecurityLog $log): int
    {
        if ($log->event_type !== SecurityLog::EVENT_TYPES['LOGIN_FAILED']) {
            return 0;
        }

        $failedAttempts = SecurityLog::where('ip_address', $log->ip_address)
            ->where('event_type', SecurityLog::EVENT_TYPES['LOGIN_FAILED'])
            ->where('created_at', '>=', now()->subHours(1))
            ->count();

        $score = 0;
        if ($failedAttempts > 5) $score += 40;
        if ($failedAttempts > 20) $score += 60;

        return $score;
    }

    /**
     * حساب درجة الخطورة بناءً على الوصول إلى مسارات حساسة.
     *
     * @param SecurityLog $log
     * @return int
     */
    protected function calculateSensitiveRouteRiskScore(SecurityLog $log): int
    {
        if (str_contains($log->route, 'admin') || str_contains($log->route, 'api')) {
            return 20;
        }

        return 0;
    }

    /**
     * تنظيف السجلات القديمة.
     */
    public function cleanOldRecords(): void
    {
        // احتفظ بالسجلات الخطيرة لمدة سنة
        SecurityLog::where('severity', '!=', SecurityLog::SEVERITY_LEVELS['CRITICAL'])
            ->where('created_at', '<=', now()->subMonths(6))
            ->chunk(1000, function ($logs) {
                $logs->each->delete();
            });

        // احتفظ بالسجلات العادية لمدة 6 أشهر
        SecurityLog::where('severity', SecurityLog::SEVERITY_LEVELS['INFO'])
            ->where('created_at', '<=', now()->subMonths(3))
            ->chunk(1000, function ($logs) {
                $logs->each->delete();
            });
    }

    /**
     * الحصول على إحصائيات سريعة.
     *
     * @return array
     */
    public function getQuickStats(): array
    {
        $cacheKey = 'security_logs_stats';
    
        return Cache::remember($cacheKey, now()->addMinutes(15), function () {
            // Single optimized query for main statistics
            $criticalLevel = SecurityLog::SEVERITY_LEVELS['CRITICAL'];
            $suspiciousType = SecurityLog::EVENT_TYPES['SUSPICIOUS_ACTIVITY'];
            $yesterday = now()->subDay();
            
            $mainStats = SecurityLog::selectRaw("
                COUNT(*) as total_events,
                COUNT(CASE WHEN severity = '{$criticalLevel}' THEN 1 END) as critical_events,
                COUNT(CASE WHEN is_resolved = 0 THEN 1 END) as unresolved_issues,
                COUNT(CASE WHEN event_type = '{$suspiciousType}' AND created_at >= ? THEN 1 END) as recent_suspicious
            ", [$yesterday])->first();

            // Separate query for blocked IPs count (needs DISTINCT)
            $blockedIpsCount = SecurityLog::where('event_type', 'blocked_access')
                ->distinct('ip_address')
                ->count('ip_address');

            // Optimized query for top attacked routes
            $warningLevel = SecurityLog::SEVERITY_LEVELS['WARNING'];
            $topAttackedRoutes = SecurityLog::select('route', DB::raw('COUNT(*) as count'))
                ->whereNotNull('route')
                ->where('severity', '>=', $warningLevel)
                ->groupBy('route')
                ->orderByDesc('count')
                ->limit(5)
                ->get();

            return [
                'total_events' => $mainStats->total_events,
                'critical_events' => $mainStats->critical_events,
                'unresolved_issues' => $mainStats->unresolved_issues,
                'recent_suspicious' => $mainStats->recent_suspicious,
                'blocked_ips' => $blockedIpsCount,
                'top_attacked_routes' => $topAttackedRoutes,
            ];
        });
    }
 
}