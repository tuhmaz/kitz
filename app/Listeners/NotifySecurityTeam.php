<?php

namespace App\Listeners;

use App\Events\SecurityThreatDetected;
use App\Models\User;
use App\Notifications\SecurityThreatNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifySecurityTeam implements ShouldQueue
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
        // Only notify for high-risk threats
        if ($event->riskScore < 75) {
            return;
        }

        try {
            // Get security team members
            $securityTeam = User::whereHas('roles', function ($query) {
                $query->where('name', 'security-admin')
                      ->orWhere('name', 'admin');
            })->get();

            if ($securityTeam->isEmpty()) {
                Log::warning('No security team members found for threat notification', [
                    'log_id' => $event->log->id,
                    'risk_score' => $event->riskScore
                ]);
                return;
            }

            // Send notification to security team
            Notification::send($securityTeam, new SecurityThreatNotification($event));

            // Log the notification
            Log::info('Security threat notification sent', [
                'log_id' => $event->log->id,
                'risk_score' => $event->riskScore,
                'threat_level' => $event->threatLevel,
                'recipients_count' => $securityTeam->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send security threat notification', [
                'log_id' => $event->log->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-queue the job for retry
            $this->fail($e);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(SecurityThreatDetected $event, \Throwable $exception): void
    {
        Log::critical('Security threat notification failed permanently', [
            'log_id' => $event->log->id,
            'risk_score' => $event->riskScore,
            'error' => $exception->getMessage()
        ]);
    }
}
