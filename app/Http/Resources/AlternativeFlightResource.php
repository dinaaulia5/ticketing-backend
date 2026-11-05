<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternativeFlightResource extends JsonResource
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

            'booking_code' =>
            $this->recommendation?->booking?->booking_code,

            'reason' => $this->reason,

            'original_flight' => [

                'flight_number' =>
                $this->originalFlight?->flight_number,

                'airline' =>
                $this->originalFlight?->airline?->name,

                'route' =>
                $this->originalFlight?->departureAirport?->code .
                    ' → ' .
                    $this->originalFlight?->arrivalAirport?->code,

            ],

            'alternative_flight' => [

                'flight_number' =>
                $this->alternativeFlight?->flight_number,

                'airline' =>
                $this->alternativeFlight?->airline?->name,

                'route' =>
                $this->alternativeFlight?->departureAirport?->code .
                    ' → ' .
                    $this->alternativeFlight?->arrivalAirport?->code,

            ],

            'created_at' => $this->created_at,
        ];
    }
}
