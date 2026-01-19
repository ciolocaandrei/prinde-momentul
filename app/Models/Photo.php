<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'filename',
        'original_name',
        'path',
        'type',
        'mime_type',
        'size',
        'uploaded_by_name',
    ];

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function getUrl(): string
    {
        return Storage::disk('r2')->temporaryUrl(
            $this->path,
            now()->addHours(24)
        );
    }
}
