<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'flight_number' => $this->flight?->flight_number,

            'booking_code' => $this->booking?->booking_code,

            'trigger_event' => $this->trigger_event,

            'delay_minutes' => $this->delay_minutes,

            'severity' => $this->severity,

            'recommendation_type' => $this->recommendation_type,

            'message' => $this->message,

            'created_at' => $this->created_at?->format('d M Y H:i'),
        ];
    }
}
