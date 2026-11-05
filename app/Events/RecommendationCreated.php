<?php

namespace App\Events;

use App\Models\AgentRecommendation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class RecommendationCreated implements ShouldBroadcastNow

// class RecommendationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public AgentRecommendation $recommendation
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('agent-recommendations'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'recommendation.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->recommendation->id,
            'booking_id' => $this->recommendation->booking_id,
            'title' => $this->recommendation->title,
            'message' => $this->recommendation->message,
            'recommendation_type' => $this->recommendation->recommendation_type,
            'severity' => $this->recommendation->severity,
            'suggested_action' => $this->recommendation->suggested_action,
        ];
    }
}
