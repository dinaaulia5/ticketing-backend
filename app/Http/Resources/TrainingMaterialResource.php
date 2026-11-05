<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingMaterialResource extends JsonResource
{
    /**
     * Transform resource menjadi array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module_id' => $this->module_id,

            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,

            'file_path' => $this->file_path,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'mime_type' => $this->mime_type,

            'video_url' => $this->video_url,

            'sequence' => $this->sequence,
            'duration_minutes' => $this->duration_minutes,
            'is_preview' => $this->is_preview,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
