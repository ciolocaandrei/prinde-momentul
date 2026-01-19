<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    wedding: Object,
    uploadCode: String,
});

const MAX_VIDEOS_PER_SESSION = 5;
const VIDEO_SESSION_KEY = `videos_uploaded_${props.uploadCode}`;

const guestName = ref('');
const files = ref([]);
const uploading = ref(false);
const uploadProgress = ref({});
const uploadedMedia = ref([]);
const errors = ref({});
const dragOver = ref(false);
const videosUploadedInSession = ref(0);

const hasFiles = computed(() => files.value.length > 0);
const selectedVideos = computed(() => files.value.filter(f => f.isVideo).length);
const selectedPhotos = computed(() => files.value.filter(f => !f.isVideo).length);
const remainingVideoSlots = computed(() => MAX_VIDEOS_PER_SESSION - videosUploadedInSession.value);
const canUploadMoreVideos = computed(() => remainingVideoSlots.value > 0);

onMounted(() => {
    // Load video count from session storage
    const stored = sessionStorage.getItem(VIDEO_SESSION_KEY);
    if (stored) {
        videosUploadedInSession.value = parseInt(stored, 10) || 0;
    }
});

const isVideoFile = (file) => {
    const videoTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
    return videoTypes.includes(file.type);
};

const isImageFile = (file) => {
    const imageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    return imageTypes.includes(file.type);
};

const handleFileSelect = (event) => {
    addFiles(event.target.files);
};

const handleDrop = (event) => {
    event.preventDefault();
    dragOver.value = false;
    addFiles(event.dataTransfer.files);
};

const handleDragOver = (event) => {
    event.preventDefault();
    dragOver.value = true;
};

const handleDragLeave = () => {
    dragOver.value = false;
};

const addFiles = (newFiles) => {
    const maxPhotoSize = 20 * 1024 * 1024; // 20MB for photos
    const maxVideoSize = 100 * 1024 * 1024; // 100MB for videos

    let videosToAdd = 0;

    const validFiles = Array.from(newFiles).filter((file) => {
        const isVideo = isVideoFile(file);
        const isImage = isImageFile(file);

        if (!isVideo && !isImage) {
            return false;
        }

        // Check file size
        if (isVideo && file.size > maxVideoSize) {
            errors.value.files = `Videoclipul ${file.name} depaseste limita de 100MB`;
            return false;
        }
        if (isImage && file.size > maxPhotoSize) {
            errors.value.files = `Fotografia ${file.name} depaseste limita de 20MB`;
            return false;
        }

        // Check video limit
        if (isVideo) {
            const currentVideos = files.value.filter(f => f.isVideo).length;
            if (currentVideos + videosToAdd >= remainingVideoSlots.value) {
                errors.value.files = `Poti incarca maxim ${MAX_VIDEOS_PER_SESSION} videoclipuri per sesiune. Mai ai ${remainingVideoSlots.value} sloturi disponibile.`;
                return false;
            }
            videosToAdd++;
        }

        return true;
    }).map(file => ({
        file,
        name: file.name,
        size: file.size,
        type: file.type,
        isVideo: isVideoFile(file),
    }));

    files.value = [...files.value, ...validFiles];
};

const removeFile = (index) => {
    files.value.splice(index, 1);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const uploadFiles = async () => {
    if (!guestName.value.trim()) {
        errors.value.name = 'Te rugam sa introduci numele tau';
        return;
    }
    if (files.value.length === 0) {
        errors.value.files = 'Te rugam sa selectezi cel putin un fisier';
        return;
    }

    errors.value = {};
    uploading.value = true;
    let videosUploaded = 0;

    for (let i = 0; i < files.value.length; i++) {
        const fileData = files.value[i];
        uploadProgress.value[i] = 0;

        const formData = new FormData();
        formData.append('file', fileData.file);
        formData.append('uploaded_by_name', guestName.value.trim());
        formData.append('type', fileData.isVideo ? 'video' : 'photo');

        try {
            const response = await axios.post(
                route('guest.upload.store', props.uploadCode),
                formData,
                {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        uploadProgress.value[i] = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total
                        );
                    },
                }
            );
            uploadedMedia.value.push({
                name: fileData.name,
                isVideo: fileData.isVideo,
                ...response.data.media
            });
            if (fileData.isVideo) {
                videosUploaded++;
            }
        } catch (error) {
            errors.value[`file_${i}`] = error.response?.data?.message || 'Upload esuat';
        }
    }

    // Update session video count
    if (videosUploaded > 0) {
        videosUploadedInSession.value += videosUploaded;
        sessionStorage.setItem(VIDEO_SESSION_KEY, videosUploadedInSession.value.toString());
    }

    uploading.value = false;
    files.value = [];
    uploadProgress.value = {};
};

const uploadMore = () => {
    uploadedMedia.value = [];
    errors.value = {};
};
</script>

<template>
    <Head :title="`Incarca Media - ${wedding.couple_name}`" />

    <div class="min-h-screen bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-50">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-slate-200 sticky top-0 z-10">
            <div class="mx-auto max-w-3xl px-4 py-4 sm:px-6">
                <div class="flex items-center justify-center gap-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-xl font-bold text-slate-900">{{ wedding.couple_name }}</h1>
                        <p class="text-sm text-slate-500">{{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { month: 'long', day: 'numeric', year: 'numeric' }) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">
            <!-- Video Limit Info -->
            <div v-if="videosUploadedInSession > 0" class="mb-6 rounded-xl bg-amber-50 border border-amber-200 p-4">
                <div class="flex items-center gap-x-3">
                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-amber-800">
                        Ai incarcat <strong>{{ videosUploadedInSession }}</strong> din <strong>{{ MAX_VIDEOS_PER_SESSION }}</strong> videoclipuri permise in aceasta sesiune.
                        <span v-if="remainingVideoSlots > 0">Mai poti incarca <strong>{{ remainingVideoSlots }}</strong> videoclipuri.</span>
                        <span v-else class="text-amber-900 font-semibold">Pentru a incarca mai multe videoclipuri, acceseaza link-ul din nou.</span>
                    </p>
                </div>
            </div>

            <!-- Success State -->
            <div v-if="uploadedMedia.length > 0 && !uploading" class="text-center">
                <div class="rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-900/5">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-2xl font-bold text-slate-900">Multumim!</h2>
                    <p class="mt-2 text-slate-500">
                        {{ uploadedMedia.length }} {{ uploadedMedia.length > 1 ? 'fisiere incarcate' : 'fisier incarcat' }} cu succes.
                    </p>
                    <div class="mt-2 flex items-center justify-center gap-x-4 text-sm text-slate-400">
                        <span v-if="uploadedMedia.filter(m => !m.isVideo).length > 0">
                            {{ uploadedMedia.filter(m => !m.isVideo).length }} fotografii
                        </span>
                        <span v-if="uploadedMedia.filter(m => m.isVideo).length > 0">
                            {{ uploadedMedia.filter(m => m.isVideo).length }} videoclipuri
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ wedding.couple_name }} va fi incantat sa vada amintirile tale!
                    </p>
                    <button
                        @click="uploadMore"
                        class="mt-8 inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Incarca Mai Multe
                    </button>
                </div>
            </div>

            <!-- Upload Form -->
            <div v-else>
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Incarca Fotografii si Videoclipuri</h2>
                    <p class="mt-2 text-slate-500">Ajuta la capturarea amintirilor acestei zile speciale</p>
                </div>

                <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-xl ring-1 ring-slate-900/5">
                    <form @submit.prevent="uploadFiles" class="space-y-6">
                        <!-- Guest Name -->
                        <div>
                            <label for="guest_name" class="block text-sm font-semibold text-slate-900">Numele Tau</label>
                            <input
                                id="guest_name"
                                type="text"
                                v-model="guestName"
                                placeholder="Introdu numele tau"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            />
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                        </div>

                        <!-- File Drop Zone -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Fotografii si Videoclipuri</label>
                            <div
                                @drop="handleDrop"
                                @dragover="handleDragOver"
                                @dragleave="handleDragLeave"
                                :class="[
                                    'relative rounded-xl border-2 border-dashed p-8 transition-all cursor-pointer',
                                    dragOver
                                        ? 'border-violet-500 bg-violet-50'
                                        : 'border-slate-300 hover:border-slate-400 hover:bg-slate-50'
                                ]"
                            >
                                <input
                                    type="file"
                                    multiple
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                    @change="handleFileSelect"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                />
                                <div class="text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-violet-100">
                                        <svg class="h-7 w-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-900">
                                        <span class="text-violet-600">Click pentru a incarca</span> sau trage si plaseaza
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">Fotografii (PNG, JPG, GIF, WebP) pana la 20MB</p>
                                    <p class="text-xs text-slate-500">Videoclipuri (MP4, MOV, AVI, WebM) pana la 100MB</p>
                                    <p v-if="canUploadMoreVideos" class="mt-2 text-xs text-amber-600 font-medium">
                                        Limita: {{ remainingVideoSlots }} videoclipuri ramase in aceasta sesiune
                                    </p>
                                    <p v-else class="mt-2 text-xs text-red-600 font-medium">
                                        Ai atins limita de videoclipuri pentru aceasta sesiune
                                    </p>
                                </div>
                            </div>
                            <p v-if="errors.files" class="mt-2 text-sm text-red-600">{{ errors.files }}</p>
                        </div>

                        <!-- Selected Files -->
                        <div v-if="hasFiles" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-900">
                                    Selectate ({{ files.length }})
                                    <span v-if="selectedPhotos > 0" class="text-slate-500 font-normal">- {{ selectedPhotos }} foto</span>
                                    <span v-if="selectedVideos > 0" class="text-slate-500 font-normal">- {{ selectedVideos }} video</span>
                                </h4>
                                <button
                                    type="button"
                                    @click="files = []"
                                    class="text-sm text-red-500 hover:text-red-600 font-medium"
                                >
                                    Sterge toate
                                </button>
                            </div>
                            <ul class="divide-y divide-slate-100 rounded-xl border border-slate-200 overflow-hidden">
                                <li
                                    v-for="(fileData, index) in files"
                                    :key="index"
                                    class="flex items-center justify-between px-4 py-3 bg-white"
                                >
                                    <div class="flex items-center gap-x-3 min-w-0">
                                        <div :class="[
                                            'flex h-10 w-10 items-center justify-center rounded-lg',
                                            fileData.isVideo ? 'bg-pink-100' : 'bg-violet-100'
                                        ]">
                                            <svg v-if="fileData.isVideo" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg v-else class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-900 truncate">{{ fileData.name }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ formatFileSize(fileData.size) }}
                                                <span :class="fileData.isVideo ? 'text-pink-600' : 'text-violet-600'" class="ml-2">
                                                    {{ fileData.isVideo ? 'Video' : 'Foto' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-x-3">
                                        <div
                                            v-if="uploading && uploadProgress[index] !== undefined"
                                            class="w-24"
                                        >
                                            <div class="h-2 rounded-full bg-slate-200 overflow-hidden">
                                                <div
                                                    :class="fileData.isVideo ? 'bg-pink-500' : 'bg-violet-500'"
                                                    class="h-full transition-all"
                                                    :style="{ width: `${uploadProgress[index]}%` }"
                                                />
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500 text-center">{{ uploadProgress[index] }}%</p>
                                        </div>
                                        <button
                                            v-else
                                            type="button"
                                            @click="removeFile(index)"
                                            class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-red-500 transition-colors"
                                        >
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="uploading || !hasFiles || !guestName.trim()"
                            class="w-full inline-flex items-center justify-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4 text-base font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                        >
                            <svg v-if="uploading" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            {{ uploading ? 'Se incarca...' : `Incarca ${files.length || ''} ${files.length !== 1 ? 'Fisiere' : 'Fisier'}` }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
