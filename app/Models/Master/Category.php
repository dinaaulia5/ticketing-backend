<?php

namespace App\Models\Master;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasUuids, HasFilter, HasSorting, SoftDeletes;
    protected $table = 'categories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    protected $searchable = [
        'code',
        'name',
        'description',
    ];

    public function subsystems(): HasMany
    {
        return $this->hasMany(Subsystem::class, 'category_id', 'id');
    }
}
