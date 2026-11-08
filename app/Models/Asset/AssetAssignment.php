<?php

namespace App\Models\Asset;

use App\Models\Employee;
use App\Models\User;
use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    use HasUuids, HasFilter, HasSorting;

    protected $table = 'asset_assignments';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'asset_id',
        'employee_id',
        'assigned_by',
        'assigned_at',
        'returned_at',
        'notes',
    ];

    protected $searchable = [
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
}
