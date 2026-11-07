<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetCategory extends Model
{
    use HasUuids;

    protected $table = 'asset_categories';

    protected $fillable = [
        'name',
        'description',
    ];
}
