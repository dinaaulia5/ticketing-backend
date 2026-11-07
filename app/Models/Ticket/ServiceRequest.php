<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id',
        'request_type',
        'item_name',
        'quantity',
        'needed_at',
        'justification',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'needed_at' => 'date',
            'details' => 'array',
        ];
    }

    // Tiket yang memiliki detail permintaan
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
