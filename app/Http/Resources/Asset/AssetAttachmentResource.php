<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Asset Information
            'asset' => [
                'id' => $this->asset?->id,
                'asset_code' => $this->asset?->asset_code,
                'name' => $this->asset?->name,
            ],

            // File Information
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_url' => $this->file_path
                ? Storage::disk('public')->url($this->file_path)
                : null,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,

            // Attachment Information
            'attachment_type' => $this->attachment_type,
            'description' => $this->description,

            // Uploader
            'uploader' => [
                'id' => $this->uploader?->id,
                'name' => $this->uploader?->name,
            ],

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
