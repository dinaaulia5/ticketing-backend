<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'training_id' => $this->training_id,

            'title' => $this->title,
            'description' => $this->description,

            'sequence' => $this->sequence,
            'duration_minutes' => $this->duration_minutes,

            'is_required' => (bool) $this->is_required,
            'is_active' => (bool) $this->is_active,

            'training' => $this->whenLoaded(
                'training',
                fn() => [
                    'id' => $this->training->id,
                    'code' => $this->training->code,
                    'title' => $this->training->title,
                ]
            ),
            'materials' => TrainingMaterialResource::collection(
                $this->whenLoaded('materials')
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
