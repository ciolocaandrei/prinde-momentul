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
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480', // 20MB max
            'uploaded_by_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Please select a photo to upload.',
            'photo.image' => 'The file must be an image.',
            'photo.mimes' => 'The photo must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'photo.max' => 'The photo must not exceed 20MB.',
            'uploaded_by_name.required' => 'Please enter your name.',
        ];
    }
}
