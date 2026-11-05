<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentRecommendationResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'booking' => [
                'id' => $this->booking?->id,
                'booking_code' => $this->booking?->booking_code,
                'contact_name' => $this->booking?->contact_name,
            ],

            'flight' => [
                'id' => $this->flight?->id,
                'flight_number' => $this->flight?->flight_number,
            ],

            'recommendation_type' => $this->recommendation_type,

            'severity' => $this->severity,

            'title' => $this->title,

            'message' => $this->message,

            'suggested_action' => $this->suggested_action,

            'is_read' => $this->is_read,

            'created_at' => $this->created_at?->format('d M Y H:i'),
        ];
    }
}
