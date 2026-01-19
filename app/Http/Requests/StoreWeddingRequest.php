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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'couple_name.required' => 'Te rugăm să introduci numele.',
            'event_type.required' => 'Te rugăm să selectezi tipul evenimentului.',
            'event_type.in' => 'Tipul evenimentului selectat nu este valid.',
            'event_date.required' => 'Te rugăm să selectezi data evenimentului.',
            'email.required' => 'Te rugăm să introduci un email.',
            'email.unique' => 'Acest email este deja înregistrat.',
            'password.required' => 'Te rugăm să introduci o parolă.',
            'password.min' => 'Parola trebuie să aibă cel puțin 8 caractere.',
        ];
    }
}
