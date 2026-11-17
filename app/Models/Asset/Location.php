<?php

namespace App\Models\Asset;

use App\Models\Asset\Asset;
use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasUuids, HasFilter, HasSorting, SoftDeletes;

    protected $table = 'locations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'location_name',
        'building',
        'floor',
        'room',
        'description',
    ];

    protected $searchable = [
        'code',
        'location_name',
        'building',
        'floor',
        'room',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'location_id', 'id');
    }
}
