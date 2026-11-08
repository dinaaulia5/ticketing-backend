<?php

namespace App\Models\Asset;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasUuids, HasFilter, HasSorting, SoftDeletes;

    protected $table = 'assets';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'asset_code',
        'name',
        'asset_category_id',
        'brand',
        'model',
        'serial_number',
        'processor',
        'ram',
        'storage',
        'os',
        'ip_address',
        'mac_address',
        'location_id',
        'purchase_date',
        'purchase_price',
        'warranty_expiry',
        'end_of_life',
        'condition',
        'status',
        'notes',
    ];

    protected $searchable = [
        'asset_code',
        'name',
        'brand',
        'model',
        'serial_number',
        'condition',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'warranty_expiry' => 'date',
            'end_of_life' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id', 'id');
    }

    // public function location(): BelongsTo
    // {
    //     return $this->belongsTo(Location::class, 'location_id', 'id');
    // }

    public function attachments(): HasMany
    {
        return $this->hasMany(AssetAttachment::class, 'asset_id', 'id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class, 'asset_id', 'id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(AssetMaintenanceLog::class, 'asset_id', 'id');
    }
}
