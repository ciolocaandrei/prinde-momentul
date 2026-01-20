<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeddingRequest;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class WeddingController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Wedding::with('user')->withCount('photos');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('couple_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Event type filter
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['created_at', 'event_date', 'couple_name'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $weddings = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Events/Index', [
            'weddings' => $weddings,
            'eventTypes' => Wedding::getEventTypeOptions(),
            'filters' => [
                'search' => $request->input('search', ''),
                'event_type' => $request->input('event_type', ''),
                'status' => $request->input('status', ''),
                'sort' => $sortField,
                'direction' => $sortDirection,
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        $prefill = null;

        if ($request->filled('from_preorder')) {
            $prefill = [
                'from_preorder' => $request->input('from_preorder'),
                'couple_name' => $request->input('name', ''),
                'event_type' => $request->input('event_type', 'nunta'),
                'event_date' => $request->input('event_date', ''),
                'email' => $request->input('email', ''),
            ];
        }

        return Inertia::render('Admin/Events/Create', [
            'eventTypes' => Wedding::getEventTypeOptions(),
            'prefill' => $prefill,
        ]);
    }

    public function store(StoreWeddingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create couple user account
        $user = User::create([
            'name' => $validated['couple_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'couple',
        ]);

        // Create wedding/event
        $wedding = Wedding::create([
            'couple_name' => $validated['couple_name'],
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'upload_code' => $this->generateUniqueCode(),
            'access_code' => $this->generateUniqueAccessCode(),
            'client_password' => $validated['password'],
            'user_id' => $user->id,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.events.show', $wedding)
            ->with('success', 'Eveniment creat cu succes.');
    }

    public function show(Wedding $wedding): Response
    {
        $wedding->load(['user', 'photos']);

        $weddingData = $wedding->toArray();
        $weddingData['event_type_label'] = $wedding->getEventTypeLabel();
        $weddingData['photos'] = $wedding->photos->map(function ($photo) {
            return array_merge($photo->toArray(), [
                'url' => $photo->getUrl(),
            ]);
        });

        return Inertia::render('Admin/Events/Show', [
            'clientPassword' => $wedding->client_password,
            'wedding' => $weddingData,
            'uploadUrl' => $wedding->getUploadUrl(),
            'accessUrl' => $wedding->getAccessUrl(),
            'eventTypes' => Wedding::getEventTypeOptions(),
        ]);
    }

    public function update(Request $request, Wedding $wedding): RedirectResponse
    {
        $validated = $request->validate([
            'couple_name' => 'sometimes|string|max:255',
            'event_type' => 'sometimes|in:nunta,majorat,zi_de_nastere,botez,altele',
            'event_date' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $wedding->update($validated);

        // Also update the associated user's name if couple_name changed
        if (isset($validated['couple_name'])) {
            $wedding->user->update(['name' => $validated['couple_name']]);
        }

        return redirect()->back()->with('success', 'Eveniment actualizat cu succes.');
    }

    public function destroy(Wedding $wedding): RedirectResponse
    {
        // Delete associated user account
        $wedding->user->delete();

        // Wedding will be cascade deleted due to foreign key constraint

        return redirect()->route('admin.events.index')
            ->with('success', 'Eveniment și cont asociat șterse.');
    }

    public function regenerateCode(Wedding $wedding): RedirectResponse
    {
        $wedding->update([
            'upload_code' => $this->generateUniqueCode(),
        ]);

        return redirect()->back()->with('success', 'Link-ul de upload a fost regenerat.');
    }

    public function regenerateAccessCode(Wedding $wedding): RedirectResponse
    {
        $wedding->update([
            'access_code' => $this->generateUniqueAccessCode(),
        ]);

        return redirect()->back()->with('success', 'Link-ul de acces a fost regenerat.');
    }

    public function gallery(Wedding $wedding): Response
    {
        $wedding->load('photos');

        $photos = $wedding->photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->getUrl(),
                'path' => $photo->path,
                'original_name' => $photo->original_name,
                'size' => $photo->size,
                'uploaded_by_name' => $photo->uploaded_by_name,
                'created_at' => $photo->created_at,
            ];
        });

        return Inertia::render('Gallery', [
            'wedding' => $wedding,
            'photos' => $photos,
            'isAdmin' => true,
            'backUrl' => route('admin.events.index'),
        ]);
    }

    public function download(Wedding $wedding): StreamedResponse
    {
        $wedding->load('photos');

        $zipFileName = Str::slug($wedding->couple_name) . '-fotografii.zip';

        return response()->streamDownload(function () use ($wedding) {
            $zip = new ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'wedding_photos_');
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($wedding->photos as $photo) {
                if (Storage::disk('r2')->exists($photo->path)) {
                    $content = Storage::disk('r2')->get($photo->path);
                    $zip->addFromString($photo->original_name, $content);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    public function downloadSelected(Request $request, Wedding $wedding): StreamedResponse
    {
        $photoIds = explode(',', $request->query('photos', ''));
        $photoIds = array_filter($photoIds, fn($id) => is_numeric($id));

        if (empty($photoIds)) {
            abort(400, 'No photos selected.');
        }

        $photos = $wedding->photos()->whereIn('id', $photoIds)->get();

        if ($photos->isEmpty()) {
            abort(404, 'No photos found.');
        }

        $zipFileName = Str::slug($wedding->couple_name) . '-selectate.zip';

        return response()->streamDownload(function () use ($photos) {
            $zip = new ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'selected_photos_');
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($photos as $photo) {
                if (Storage::disk('r2')->exists($photo->path)) {
                    $content = Storage::disk('r2')->get($photo->path);
                    $zip->addFromString($photo->original_name, $content);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(8);
        } while (Wedding::where('upload_code', $code)->exists());

        return $code;
    }

    private function generateUniqueAccessCode(): string
    {
        do {
            $code = Str::random(24);
        } while (Wedding::where('access_code', $code)->exists());

        return $code;
    }
}
