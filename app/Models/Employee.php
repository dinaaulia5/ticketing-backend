<?php

namespace App\Models;

use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasUuids, HasFilter, HasSorting, SoftDeletes;
    protected $table = 'employees';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'auth_id',
        'nup',
        'division',
        'position',
        'departement_code',
        'employee_status',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
