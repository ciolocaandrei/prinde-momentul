<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePreorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'event_type' => 'required|in:nunta,majorat,botez',
            'event_date' => 'nullable|date|after_or_equal:today',
            'wants_print_package' => 'boolean',
            'wants_qr_card' => 'boolean',
            'qr_card_quantity' => 'nullable|integer|min:1|max:1000',
            'qr_card_image' => 'nullable|image|max:5120',
            'qr_card_sample' => 'nullable|string|max:255',
            'qr_card_sample_names' => 'nullable|string|max:255',
            'qr_card_sample_date' => 'nullable|string|max:255',
            'qr_card_theme' => 'nullable|string|max:500',
            'qr_card_design' => 'nullable',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'contact_name.required' => 'Te rugăm să introduci numele tău.',
            'contact_email.required' => 'Te rugăm să introduci adresa de email.',
            'contact_email.email' => 'Te rugăm să introduci o adresă de email validă.',
            'event_type.required' => 'Te rugăm să selectezi tipul evenimentului.',
            'event_type.in' => 'Tipul evenimentului selectat nu este valid.',
            'event_date.after_or_equal' => 'Data evenimentului trebuie să fie în viitor.',
            'qr_card_image.image' => 'Fișierul încărcat trebuie să fie o imagine.',
            'qr_card_image.max' => 'Imaginea nu poate depăși 5MB.',
            'qr_card_theme.max' => 'Descrierea temei nu poate depăși 500 de caractere.',
            'notes.max' => 'Observațiile nu pot depăși 1000 de caractere.',
        ];
    }
}
