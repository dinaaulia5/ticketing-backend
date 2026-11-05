<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingResource extends JsonResource
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

            'code' => $this->code,
            'title' => $this->title,
            'slug' => $this->slug,

            'description' => $this->description,

            'category_id' => $this->category_id,
            'type_id' => $this->type_id,
            'modules' => $this->whenLoaded('modules'),
            'materials' => TrainingMaterialResource::collection(
                $this->whenLoaded('materials')
            ),
            'category' => $this->whenLoaded(
                'category',
                fn() => [
                    'id' => $this->category->id,
                    'code' => $this->category->code,
                    'name' => $this->category->name,
                ]
            ),

            'type' => $this->whenLoaded(
                'type',
                fn() => [
                    'id' => $this->type->id,
                    'code' => $this->type->code,
                    'name' => $this->type->name,
                ]
            ),

            'level' => $this->level,
            'duration_minutes' => $this->duration_minutes,
            'capacity' => $this->capacity,

            'is_mandatory' => (bool) $this->is_mandatory,

            'status' => $this->status,

            'thumbnail' => $this->thumbnail,

            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),

            'registration_start' =>
            $this->registration_start?->format('Y-m-d'),

            'registration_end' =>
            $this->registration_end?->format('Y-m-d'),

            'created_by' => $this->created_by,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
