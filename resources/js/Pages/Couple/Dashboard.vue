<script setup>
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    wedding: Object,
    photos: Array,
});

const photos = ref(props.photos);
const lastFetch = ref(new Date().toISOString());
const selectedPhoto = ref(null);
const copied = ref(false);
let pollInterval = null;

const fetchNewPhotos = async () => {
    try {
        const response = await axios.get(route('couple.photos'), {
            params: { since: lastFetch.value },
        });

        if (response.data.photos.length > 0) {
            photos.value = [...response.data.photos, ...photos.value];
        }

        lastFetch.value = response.data.timestamp;
    } catch (error) {
        console.error('Error fetching photos:', error);
    }
};

onMounted(() => {
    pollInterval = setInterval(fetchNewPhotos, 5000);
});

onUnmounted(() => {
    if (pollInterval) {
        clearInterval(pollInterval);
    }
});

const copyUploadUrl = async () => {
    await navigator.clipboard.writeText(props.wedding.upload_url);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
};

const openLightbox = (photo) => {
    selectedPhoto.value = photo;
};

const closeLightbox = () => {
    selectedPhoto.value = null;
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
};

const totalSize = computed(() => {
    return photos.value.reduce((sum, photo) => sum + photo.size, 0);
});

const navigatePhoto = (direction) => {
    const currentIndex = photos.value.findIndex(p => p.id === selectedPhoto.value.id);
    let newIndex = currentIndex + direction;
    if (newIndex < 0) newIndex = photos.value.length - 1;
    if (newIndex >= photos.value.length) newIndex = 0;
    selectedPhoto.value = photos.value[newIndex];
};
</script>

<template>
    <Head title="Fotografiile Mele" />

    <CoupleLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ wedding.couple_name }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) }}
                </p>
            </div>
            <a
                v-if="photos.length > 0"
                :href="route('couple.download')"
                class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 hover:bg-slate-800 transition-all"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descarcă Tot
            </a>
        </template>

        <!-- Share Card -->
        <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-lg mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold">Distribuie Invitaților</h2>
                    <p class="mt-1 text-slate-300 text-sm">Trimite acest link pentru a încărca fotografii</p>
                </div>
                <div class="flex items-center gap-3">
                    <input
                        type="text"
                        :value="wedding.upload_url"
                        readonly
                        class="w-full sm:w-80 rounded-xl border-0 bg-white/10 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-white"
                    />
                    <button
                        @click="copyUploadUrl"
                        class="inline-flex items-center gap-x-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-slate-100 transition-colors whitespace-nowrap"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        {{ copied ? 'Copiat!' : 'Copiază' }}
                    </button>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-x-2 text-sm">
                <span :class="[wedding.is_active ? 'bg-emerald-400' : 'bg-red-400', 'h-2 w-2 rounded-full']" />
                <span class="text-slate-300">{{ wedding.is_active ? 'Upload-ul este activ' : 'Upload-ul este oprit' }}</span>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ photos.length }}</p>
                        <p class="text-sm text-slate-500">Total Fotografii</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-rose-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ formatFileSize(totalSize) }}</p>
                        <p class="text-sm text-slate-500">Spațiu Utilizat</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-600">Actualizări Live</p>
                        <p class="text-sm text-slate-500">Se reîmprospătează la 5s</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Gallery -->
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Galerie Foto</h2>
                <Link
                    v-if="photos.length > 0"
                    :href="route('couple.gallery')"
                    class="inline-flex items-center gap-x-1.5 text-sm font-medium text-rose-500 hover:text-rose-600 transition-colors"
                >
                    Vezi Galeria Completă
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>
            <div class="p-6">
                <div v-if="photos.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    <div
                        v-for="photo in photos"
                        :key="photo.id"
                        @click="openLightbox(photo)"
                        class="group relative aspect-square cursor-pointer overflow-hidden rounded-xl bg-slate-100"
                    >
                        <img
                            :src="photo.url"
                            :alt="photo.original_name"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <p class="text-sm font-medium text-white truncate">{{ photo.uploaded_by_name }}</p>
                                <p class="text-xs text-slate-300">{{ formatFileSize(photo.size) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="py-20 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-lg font-semibold text-slate-900">Nicio fotografie încă</h3>
                    <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">
                        Distribuie linkul de upload invitaților și privește cum încep să apară amintirile!
                    </p>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <Teleport to="body">
            <div
                v-if="selectedPhoto"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/95"
                @click.self="closeLightbox"
            >
                <!-- Close button -->
                <button
                    @click="closeLightbox"
                    class="absolute right-4 top-4 rounded-full bg-white/10 p-2.5 text-white hover:bg-white/20 transition-colors z-10"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Navigation arrows -->
                <button
                    @click="navigatePhoto(-1)"
                    class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3.5 text-white hover:bg-white/20 transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    @click="navigatePhoto(1)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-3.5 text-white hover:bg-white/20 transition-colors"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Image and info -->
                <div class="max-h-[90vh] max-w-[90vw] flex flex-col items-center">
                    <img
                        :src="selectedPhoto.url"
                        :alt="selectedPhoto.original_name"
                        class="max-h-[80vh] max-w-full object-contain rounded-xl"
                    />
                    <div class="mt-4 text-center">
                        <p class="text-white font-medium">Încărcat de {{ selectedPhoto.uploaded_by_name }}</p>
                        <p class="text-sm text-slate-400">{{ formatDate(selectedPhoto.created_at) }}</p>
                    </div>
                </div>
            </div>
        </Teleport>
    </CoupleLayout>
</template>
