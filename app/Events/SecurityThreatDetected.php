<?php

namespace App\Events;

use App\Models\SecurityLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SecurityThreatDetected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SecurityLog $log;
    public int $riskScore;
    public string $threatLevel;
    public array $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct(SecurityLog $log, int $riskScore, string $threatLevel = 'medium', array $metadata = [])
    {
        $this->log = $log;
        $this->riskScore = $riskScore;
        $this->threatLevel = $threatLevel;
        $this->metadata = $metadata;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('security-alerts'),
            new PrivateChannel('security-dashboard'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->log->id,
            'ip_address' => $this->log->ip_address,
            'event_type' => $this->log->event_type,
            'severity' => $this->log->severity,
            'risk_score' => $this->riskScore,
            'threat_level' => $this->threatLevel,
            'description' => $this->log->description,
            'country_code' => $this->log->country_code,
            'city' => $this->log->city,
            'created_at' => $this->log->created_at->toISOString(),
            'metadata' => $this->metadata,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'threat.detected';
    }
}
