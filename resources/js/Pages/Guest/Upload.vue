<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    wedding: Object,
    uploadCode: String,
});

const guestName = ref('');
const files = ref([]);
const uploading = ref(false);
const uploadProgress = ref({});
const uploadedPhotos = ref([]);
const errors = ref({});
const dragOver = ref(false);

const hasFiles = computed(() => files.value.length > 0);

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
    const validFiles = Array.from(newFiles).filter((file) => {
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        return validTypes.includes(file.type) && file.size <= 20 * 1024 * 1024;
    });
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
        errors.value.name = 'Te rugăm să introduci numele tău';
        return;
    }
    if (files.value.length === 0) {
        errors.value.files = 'Te rugăm să selectezi cel puțin o fotografie';
        return;
    }

    errors.value = {};
    uploading.value = true;

    for (let i = 0; i < files.value.length; i++) {
        const file = files.value[i];
        uploadProgress.value[i] = 0;

        const formData = new FormData();
        formData.append('photo', file);
        formData.append('uploaded_by_name', guestName.value.trim());

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
            uploadedPhotos.value.push({ name: file.name, ...response.data.photo });
        } catch (error) {
            errors.value[`file_${i}`] = error.response?.data?.message || 'Upload failed';
        }
    }

    uploading.value = false;
    files.value = [];
    uploadProgress.value = {};
};

const uploadMore = () => {
    uploadedPhotos.value = [];
    errors.value = {};
};
</script>

<template>
    <Head :title="`Încarcă Fotografii - ${wedding.couple_name}`" />

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
            <!-- Success State -->
            <div v-if="uploadedPhotos.length > 0 && !uploading" class="text-center">
                <div class="rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-900/5">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="mt-6 text-2xl font-bold text-slate-900">Mulțumim!</h2>
                    <p class="mt-2 text-slate-500">
                        {{ uploadedPhotos.length }} {{ uploadedPhotos.length > 1 ? 'fotografii încărcate' : 'fotografie încărcată' }} cu succes.
                    </p>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ wedding.couple_name }} va fi încântat să vadă amintirile tale!
                    </p>
                    <button
                        @click="uploadMore"
                        class="mt-8 inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Încarcă Mai Multe
                    </button>
                </div>
            </div>

            <!-- Upload Form -->
            <div v-else>
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Încarcă Fotografiile Tale</h2>
                    <p class="mt-2 text-slate-500">Ajută la capturarea amintirilor acestei zile speciale</p>
                </div>

                <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-xl ring-1 ring-slate-900/5">
                    <form @submit.prevent="uploadFiles" class="space-y-6">
                        <!-- Guest Name -->
                        <div>
                            <label for="guest_name" class="block text-sm font-semibold text-slate-900">Numele Tău</label>
                            <input
                                id="guest_name"
                                type="text"
                                v-model="guestName"
                                placeholder="Introdu numele tău"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            />
                            <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                        </div>

                        <!-- File Drop Zone -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-900 mb-2">Fotografii</label>
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
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                    @change="handleFileSelect"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                />
                                <div class="text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-violet-100">
                                        <svg class="h-7 w-7 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-slate-900">
                                        <span class="text-violet-600">Click pentru a încărca</span> sau trage și plasează
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">PNG, JPG, GIF, WebP până la 20MB fiecare</p>
                                </div>
                            </div>
                            <p v-if="errors.files" class="mt-2 text-sm text-red-600">{{ errors.files }}</p>
                        </div>

                        <!-- Selected Files -->
                        <div v-if="hasFiles" class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-900">Selectate ({{ files.length }})</h4>
                                <button
                                    type="button"
                                    @click="files = []"
                                    class="text-sm text-red-500 hover:text-red-600 font-medium"
                                >
                                    Șterge toate
                                </button>
                            </div>
                            <ul class="divide-y divide-slate-100 rounded-xl border border-slate-200 overflow-hidden">
                                <li
                                    v-for="(file, index) in files"
                                    :key="index"
                                    class="flex items-center justify-between px-4 py-3 bg-white"
                                >
                                    <div class="flex items-center gap-x-3 min-w-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100">
                                            <svg class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-slate-900 truncate">{{ file.name }}</p>
                                            <p class="text-xs text-slate-500">{{ formatFileSize(file.size) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-x-3">
                                        <div
                                            v-if="uploading && uploadProgress[index] !== undefined"
                                            class="w-24"
                                        >
                                            <div class="h-2 rounded-full bg-slate-200 overflow-hidden">
                                                <div
                                                    class="h-full bg-violet-500 transition-all"
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
                            {{ uploading ? 'Se încarcă...' : `Încarcă ${files.length || ''} ${files.length !== 1 ? 'Fotografii' : 'Fotografie'}` }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
