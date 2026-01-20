<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeddingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'couple_name' => 'required|string|max:255',
            'event_type' => 'required|in:nunta,majorat,zi_de_nastere,botez,altele',
            'event_date' => 'required|date',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'couple_name.required' => 'Te rugam sa introduci numele.',
            'event_type.required' => 'Te rugam sa selectezi tipul evenimentului.',
            'event_type.in' => 'Tipul evenimentului selectat nu este valid.',
            'event_date.required' => 'Te rugam sa selectezi data evenimentului.',
            'email.email' => 'Te rugam sa introduci un email valid.',
        ];
    }
}
