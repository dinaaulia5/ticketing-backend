<?php

namespace App\Http\Resources\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asset_code' => $this->asset_code,
            'name' => $this->name,

            // Category
            'category' => [
                'id' => $this->asset_category_id,
                'code' => $this->category?->code,
                'name' => $this->category?->name,
            ],

            // Asset Information
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,

            // Technical Specifications
            'specifications' => [
                'processor' => $this->processor,
                'ram' => $this->ram,
                'storage' => $this->storage,
                'os' => $this->os,
                'ip_address' => $this->ip_address,
                'mac_address' => $this->mac_address,
            ],

            // Location
            'location' => [
                'id' => $this->location_id,
                'name' => $this->location?->location_name,
            ],

            // Purchase Information
            'purchase_date' => $this->purchase_date?->format('Y-m-d'),
            'purchase_price' => $this->purchase_price,
            'warranty_expiry' => $this->warranty_expiry?->format('Y-m-d'),
            'end_of_life' => $this->end_of_life?->format('Y-m-d'),

            // Condition & Status
            'condition' => $this->condition,
            'status' => $this->status,

            // Additional Information
            'notes' => $this->notes,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
