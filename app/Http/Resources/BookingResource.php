<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'booking_code' => $this->booking_code,

            'contact_name' => $this->contact_name,

            'contact_email' => $this->contact_email,

            'contact_phone' => $this->contact_phone,

            'seat_class' => ucfirst($this->seat_class),

            'booking_status' => $this->booking_status,

            'payment_status' => $this->payment_status,

            'passenger_count' => $this->passenger_count,

            'total_price' => $this->total_price,

            'created_at' => $this->created_at->format('d M Y H:i'),

            'flight' => [

                'flight_number' => $this->flight?->flight_number,

                'airline' => $this->flight?->airline?->name,

                'departure' => $this->flight?->departureAirport?->code,

                'arrival' => $this->flight?->arrivalAirport?->code,

                'departure_time' => $this->flight?->scheduled_departure,

                'arrival_time' => $this->flight?->scheduled_arrival,
            ],

            'recommendation' => AgentRecommendationResource::make(
                $this->recommendations->first()
            ),
        ];
    }
}
