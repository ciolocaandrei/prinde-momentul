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
        $file = $request->file('file');
        $type = $validated['type'];

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $folder = $type === 'video' ? 'videos' : 'photos';
        $path = "weddings/{$wedding->id}/{$folder}/{$filename}";

        // Store the file in Cloudflare R2
        $file->storeAs("weddings/{$wedding->id}/{$folder}", $filename, 'r2');

        // Create photo/video record
        $photo = Photo::create([
            'wedding_id' => $wedding->id,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by_name' => $validated['uploaded_by_name'],
        ]);

        return response()->json([
            'message' => $type === 'video' ? 'Video incarcat cu succes.' : 'Fotografie incarcata cu succes.',
            'media' => [
                'id' => $photo->id,
                'original_name' => $photo->original_name,
                'type' => $photo->type,
            ],
        ]);
    }
}
