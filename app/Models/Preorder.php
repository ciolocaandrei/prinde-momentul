<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Preorder extends Model
{
    use HasFactory;

    public const EVENT_TYPES = [
        'nunta' => 'Nuntă',
        'majorat' => 'Majorat',
        'zi_de_nastere' => 'Zi de Naștere',
        'botez' => 'Botez',
        'corporate' => 'Eveniment Corporate',
        'altele' => 'Alt Eveniment',
    ];

    public const STATUSES = [
        'pending' => 'În așteptare',
        'confirmed' => 'Confirmat',
        'completed' => 'Finalizat',
        'cancelled' => 'Anulat',
    ];

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone',
        'event_type',
        'event_date',
        'wants_print_package',
        'wants_qr_card',
        'qr_card_quantity',
        'qr_card_image',
        'qr_card_theme',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'wants_print_package' => 'boolean',
            'wants_qr_card' => 'boolean',
        ];
    }

    public function getEventTypeLabel(): string
    {
        return self::EVENT_TYPES[$this->event_type] ?? $this->event_type;
    }

    public function getStatusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getQrCardImageUrl(): ?string
    {
        if ($this->qr_card_image) {
            return Storage::url($this->qr_card_image);
        }
        return null;
    }

    public static function getEventTypeOptions(): array
    {
        return self::EVENT_TYPES;
    }

    public static function getStatusOptions(): array
    {
        return self::STATUSES;
    }
}
