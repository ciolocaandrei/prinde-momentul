<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wedding extends Model
{
    use HasFactory;

    public const EVENT_TYPES = [
        'nunta' => 'Nuntă',
        'majorat' => 'Majorat',
        'zi_de_nastere' => 'Zi de Naștere',
        'botez' => 'Botez',
        'altele' => 'Alt Eveniment',
    ];

    protected $fillable = [
        'couple_name',
        'event_type',
        'event_date',
        'upload_code',
        'access_code',
        'client_password',
        'user_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function getUploadUrl(): string
    {
        return url("/upload/{$this->upload_code}");
    }

    public function getAccessUrl(): string
    {
        return url("/access/{$this->access_code}");
    }

    public function getEventTypeLabel(): string
    {
        return self::EVENT_TYPES[$this->event_type] ?? $this->event_type;
    }

    public static function getEventTypeOptions(): array
    {
        return self::EVENT_TYPES;
    }
}
