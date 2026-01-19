<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public upload - no auth required
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:512000', // 500MB max
            'uploaded_by_name' => 'required|string|max:255',
            'type' => 'required|in:photo,video',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Te rugam sa selectezi un fisier.',
            'file.file' => 'Fisierul nu este valid.',
            'file.mimes' => 'Fisierul trebuie sa fie JPEG, PNG, JPG, GIF, WebP, MP4, MOV, AVI sau WebM.',
            'file.max' => 'Fisierul nu poate depasi 500MB.',
            'uploaded_by_name.required' => 'Te rugam sa introduci numele tau.',
            'type.required' => 'Tipul fisierului este obligatoriu.',
            'type.in' => 'Tipul fisierului nu este valid.',
        ];
    }
}
