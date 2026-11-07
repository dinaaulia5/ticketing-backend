<?php

namespace App\Models\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'is_internal',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
        ];
    }

    // Tiket terkait
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    // Pengguna yang memberikan komentar
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
