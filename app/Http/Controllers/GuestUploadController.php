<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPhotoRequest;
use App\Models\Photo;
use App\Models\Wedding;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GuestUploadController extends Controller
{
    public function show(string $code): Response
    {
        $wedding = Wedding::where('upload_code', $code)->firstOrFail();

        if (!$wedding->is_active) {
            abort(403, 'Uploads are currently disabled for this wedding.');
        }

        return Inertia::render('Guest/Upload', [
            'wedding' => [
                'couple_name' => $wedding->couple_name,
                'event_date' => $wedding->event_date->format('Y-m-d'),
            ],
            'uploadCode' => $code,
        ]);
    }

    public function upload(UploadPhotoRequest $request, string $code): JsonResponse
    {
        $wedding = Wedding::where('upload_code', $code)->firstOrFail();

        if (!$wedding->is_active) {
            return response()->json(['message' => 'Uploads are disabled.'], 403);
        }

        $validated = $request->validated();
        $file = $request->file('photo');

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = "weddings/{$wedding->id}/{$filename}";

        // Store the file in Cloudflare R2
        $file->storeAs("weddings/{$wedding->id}", $filename, 'r2');

        // Create photo record
        $photo = Photo::create([
            'wedding_id' => $wedding->id,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'uploaded_by_name' => $validated['uploaded_by_name'],
        ]);

        return response()->json([
            'message' => 'Photo uploaded successfully.',
            'photo' => [
                'id' => $photo->id,
                'original_name' => $photo->original_name,
            ],
        ]);
    }
}
