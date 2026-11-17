<?php

namespace App\Models\Asset;

use App\Models\User;
use App\Traits\HasFilter;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAttachment extends Model
{
    use HasUuids, HasFilter, HasSorting;

    protected $table = 'asset_attachments';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'asset_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'attachment_type',
        'description',
        'uploaded_by',
    ];

    protected $searchable = [
        'file_name',
        'file_type',
        'attachment_type',
        'description',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id');
    }
}
