<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Carbon\Carbon;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'event_type',
        'description',
        'user_id',
        'route',
        'request_data',
        'severity',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
        'country_code',
        'city',
        'attack_type',
        'risk_score',
    ];

    protected $casts = [
        'request_data' => 'encrypted:array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'risk_score' => 'integer',
    ];

    // أنواع الأحداث
    const EVENT_TYPES = [
        'LOGIN_SUCCESS' => 'login',
        'LOGIN_FAILED' => 'failed_login',
        'LOGOUT' => 'logout',
        'PASSWORD_RESET' => 'password_reset',
        'PROFILE_UPDATE' => 'profile_update',
        'SETTINGS_CHANGE' => 'settings_change',
        'API_ACCESS' => 'api_access',
        'SUSPICIOUS_ACTIVITY' => 'suspicious_activity',
        'BLOCKED_ACCESS' => 'blocked_access',
        'DATA_EXPORT' => 'data_export',
        'PERMISSION_CHANGE' => 'permission_change',
        'FILE_ACCESS' => 'file_access',
        'ADMIN_ACTION' => 'admin_action',
    ];

    // مستويات الخطورة
    const SEVERITY_LEVELS = [
        'INFO' => 'info',
        'WARNING' => 'warning',
        'DANGER' => 'danger',
        'CRITICAL' => 'critical',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المستخدم الذي قام بحل المشكلة
    public function resolvedByUser()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // الحصول على لون نوع الحدث
    public function getEventTypeColorAttribute(): string
    {
        return match($this->event_type) {
            self::EVENT_TYPES['LOGIN_SUCCESS'] => 'success',
            self::EVENT_TYPES['LOGOUT'] => 'info',
            self::EVENT_TYPES['LOGIN_FAILED'] => 'danger',
            self::EVENT_TYPES['PASSWORD_RESET'] => 'warning',
            self::EVENT_TYPES['PROFILE_UPDATE'] => 'primary',
            self::EVENT_TYPES['SETTINGS_CHANGE'] => 'secondary',
            self::EVENT_TYPES['SUSPICIOUS_ACTIVITY'] => 'danger',
            self::EVENT_TYPES['BLOCKED_ACCESS'] => 'dark',
            self::EVENT_TYPES['DATA_EXPORT'] => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get the severity badge color.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            self::SEVERITY_LEVELS['INFO'] => 'info',
            self::SEVERITY_LEVELS['WARNING'] => 'warning',
            self::SEVERITY_LEVELS['DANGER'] => 'danger',
            self::SEVERITY_LEVELS['CRITICAL'] => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check if the log is high risk.
     */
    public function isHighRisk(): bool
    {
        return $this->risk_score >= 75;
    }

    /**
     * Check if the log is critical.
     */
    public function isCritical(): bool
    {
        return $this->severity === self::SEVERITY_LEVELS['CRITICAL'];
    }

    /**
     * Get formatted risk level.
     */
    public function getRiskLevelAttribute(): string
    {
        return match(true) {
            $this->risk_score >= 90 => 'Critical',
            $this->risk_score >= 75 => 'High',
            $this->risk_score >= 50 => 'Medium',
            $this->risk_score >= 25 => 'Low',
            default => 'Minimal'
        };
    }

    // Scope للأحداث غير المحلولة
    public function scopeUnresolved(Builder $query)
    {
        return $query->where('is_resolved', false);
    }

    // Scope للأحداث الحرجة
    public function scopeCritical(Builder $query)
    {
        return $query->where('severity', self::SEVERITY_LEVELS['CRITICAL']);
    }

    // Scope للأحداث الأخيرة
    public function scopeRecent(Builder $query, int $hours = 24): Builder
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Scope للأحداث عالية الخطورة
    public function scopeHighRisk(Builder $query): Builder
    {
        return $query->where('risk_score', '>=', 75);
    }

    // Scope للأحداث حسب نوع الحدث
    public function scopeByEventType(Builder $query, string $eventType): Builder
    {
        return $query->where('event_type', $eventType);
    }

    // Scope للأحداث حسب IP
    public function scopeByIp(Builder $query, string $ip): Builder
    {
        return $query->where('ip_address', $ip);
    }

    // Scope للأحداث في فترة زمنية
    public function scopeDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate risk score when creating
        static::creating(function ($log) {
            if (!$log->risk_score) {
                $log->risk_score = static::calculateRiskScore($log);
            }
        });
    }

    /**
     * Calculate basic risk score.
     */
    protected static function calculateRiskScore($log): int
    {
        $score = 0;

        // Base score by event type
        $score += match($log->event_type) {
            self::EVENT_TYPES['LOGIN_FAILED'] => 30,
            self::EVENT_TYPES['SUSPICIOUS_ACTIVITY'] => 50,
            self::EVENT_TYPES['BLOCKED_ACCESS'] => 40,
            self::EVENT_TYPES['UNAUTHORIZED_ACCESS'] => 60,
            default => 10
        };

        // Additional score by severity
        $score += match($log->severity) {
            self::SEVERITY_LEVELS['CRITICAL'] => 40,
            self::SEVERITY_LEVELS['DANGER'] => 30,
            self::SEVERITY_LEVELS['WARNING'] => 20,
            default => 0
        };

        return min($score, 100);
    }
}