<?php

namespace App\Models\Ticket;

use App\Models\Master\Category;
use App\Models\Master\Subsystem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasUuids, HasFilter, HasSorting, SoftDeletes;
    protected $table = 'tickets';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'created_by',
        'ticket_type',
        'category_id',
        'subsystem_id',
        'title',
        'description',
        'impact',
        'urgency',
        'priority',
        'status',
        'source',
        'closed_at',
    ];

    protected $searchable = [
        'ticket_number',
        'user_id',
        'created_by',
        'ticket_type',
        'category_id',
        'subsystem_id',
        'title',
        'description',
        'impact',
        'urgency',
        'priority',
        'status',
        'source',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }
    // generate ticket number
    public static function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'TCK-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (
            self::withTrashed()->where('ticket_number', $ticketNumber)->exists()
        );

        return $ticketNumber;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subsystem(): BelongsTo
    {
        return $this->belongsTo(Subsystem::class, 'subsystem_id');
    }



    // Lampiran tiket
    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    // Riwayat penugasan teknisi
    public function assignments(): HasMany
    {
        return $this->hasMany(TicketAssignment::class);
    }

    // Penugasan aktif
    public function activeAssignments(): HasMany
    {
        return $this->hasMany(TicketAssignment::class)
            ->whereNull('unassigned_at');
    }

    // Riwayat aktivitas tiket
    public function histories(): HasMany
    {
        return $this->hasMany(TicketHistory::class);
    }

    // Komentar tiket
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    // Detail service request
    public function serviceRequest(): HasOne
    {
        return $this->hasOne(ServiceRequest::class);
    }
}
