<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'flight_number' => $this->flight_number,
            'scheduled_departure' => $this->scheduled_departure,
            'scheduled_arrival' => $this->scheduled_arrival,
            'price' => $this->price,
            'airline' => [
                'id' => $this->airline?->id,
                'name' => $this->airline?->name,
                'code' => $this->airline?->code,
                'logo' => $this->airline?->logo,
            ],
            'departure_airport' => [
                'id' => $this->departureAirport?->id,
                'code' => $this->departureAirport?->code,
                'name' => $this->departureAirport?->name,
                'city' => $this->departureAirport?->city,
                'country' => $this->departureAirport?->country,
            ],
            'arrival_airport' => [
                'id' => $this->arrivalAirport?->id,
                'code' => $this->arrivalAirport?->code,
                'name' => $this->arrivalAirport?->name,
                'city' => $this->arrivalAirport?->city,
                'country' => $this->arrivalAirport?->country,
            ],
            'flight_status' => [
                'id' => $this->flightStatus?->id,
                'status' => $this->flightStatus?->status,
                'delay_minutes' => $this->flightStatus?->delay_minutes,
                'reason' => $this->flightStatus?->reason,
                'actual_departure' => $this->flightStatus?->actual_departure,
                'actual_arrival' => $this->flightStatus?->actual_arrival,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
