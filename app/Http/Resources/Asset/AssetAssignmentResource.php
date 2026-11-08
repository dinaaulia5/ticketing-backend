<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetAssignmentResource extends JsonResource
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
                'brand' => $this->asset?->brand,
                'model' => $this->asset?->model,
            ],

            // Employee Information
            'employee' => [
                'id' => $this->employee?->id,
                'name' => $this->employee?->name,
                'nup' => $this->employee?->nup,
            ],

            // Assignment Information
            'assigned_by' => [
                'id' => $this->assignedBy?->id,
                'name' => $this->assignedBy?->name,
            ],

            'assigned_at' => $this->assigned_at?->format('Y-m-d H:i:s'),
            'returned_at' => $this->returned_at?->format('Y-m-d H:i:s'),

            'status' => $this->returned_at
                ? 'returned'
                : 'assigned',

            'notes' => $this->notes,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
