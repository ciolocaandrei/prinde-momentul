<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CoupleLayout from '@/Layouts/CoupleLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, defineAsyncComponent } from 'vue';

const props = defineProps({
    wedding: Object,
    photos: Array,
    isAdmin: Boolean,
    backUrl: String,
});

const eventTypeLabels = {
    nunta: 'Nuntă',
    majorat: 'Majorat',
    zi_de_nastere: 'Zi de Naștere',
    botez: 'Botez',
    altele: 'Alt Eveniment',
};

const photos = ref(props.photos);
const selectedPhoto = ref(null);
const selectedIndex = ref(0);
const selectedPhotos = ref(new Set());
const selectMode = ref(false);
const downloading = ref(false);
const sortBy = ref('date');
const searchQuery = ref('');

const Layout = computed(() => props.isAdmin ? AdminLayout : CoupleLayout);

const filteredAndSortedPhotos = computed(() => {
    let result = [...photos.value];

    // Filter by search query
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase().trim();
        result = result.filter(photo =>
            photo.uploaded_by_name.toLowerCase().includes(query)
        );
    }

    // Sort
    if (sortBy.value === 'name') {
        result.sort((a, b) => a.uploaded_by_name.localeCompare(b.uploaded_by_name));
    } else {
        result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }

    return result;
});

// Keep sortedPhotos as alias for compatibility
const sortedPhotos = filteredAndSortedPhotos;

const toggleSelectMode = () => {
    selectMode.value = !selectMode.value;
    if (!selectMode.value) {
        selectedPhotos.value.clear();
    }
};

const togglePhotoSelection = (photoId, event) => {
    event.stopPropagation();
    if (selectedPhotos.value.has(photoId)) {
        selectedPhotos.value.delete(photoId);
    } else {
        selectedPhotos.value.add(photoId);
    }
};

const selectAll = () => {
    photos.value.forEach(photo => selectedPhotos.value.add(photo.id));
};

const deselectAll = () => {
    selectedPhotos.value.clear();
};

const downloadSelected = async () => {
    if (selectedPhotos.value.size === 0) return;

    downloading.value = true;

    const selectedIds = Array.from(selectedPhotos.value);
    const url = props.isAdmin
        ? route('admin.events.download-selected', { wedding: props.wedding.id, photos: selectedIds.join(',') })
        : route('couple.download-selected', { photos: selectedIds.join(',') });

    window.location.href = url;

    setTimeout(() => {
        downloading.value = false;
    }, 2000);
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('ro-RO', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const totalSize = computed(() => {
    return photos.value.reduce((sum, photo) => sum + photo.size, 0);
});

const openLightbox = (photo, index) => {
    selectedPhoto.value = photo;
    selectedIndex.value = index;
};

const closeLightbox = () => {
    selectedPhoto.value = null;
};

const navigatePhoto = (direction) => {
    let newIndex = selectedIndex.value + direction;
    if (newIndex < 0) newIndex = sortedPhotos.value.length - 1;
    if (newIndex >= sortedPhotos.value.length) newIndex = 0;
    selectedIndex.value = newIndex;
    selectedPhoto.value = sortedPhotos.value[newIndex];
};

const handleKeydown = (e) => {
    if (!selectedPhoto.value) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigatePhoto(-1);
    if (e.key === 'ArrowRight') navigatePhoto(1);
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <Head :title="`Galerie - ${wedding.couple_name}`" />

    <component :is="Layout">
        <template #header>
            <div>
                <nav class="flex items-center gap-x-2 text-sm">
                    <Link :href="backUrl" class="text-slate-500 hover:text-slate-700 transition-colors">
                        {{ isAdmin ? 'Evenimente' : 'Dashboard' }}
                    </Link>
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <Link v-if="isAdmin" :href="route('admin.events.show', wedding.id)" class="text-slate-500 hover:text-slate-700 transition-colors">
                        {{ wedding.couple_name }}
                    </Link>
                    <svg v-if="isAdmin" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-medium">Galerie</span>
                </nav>
                <div class="mt-2 flex items-center gap-x-3">
                    <h1 class="text-2xl font-bold text-slate-900">Galerie Foto</h1>
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                        {{ eventTypeLabels[wedding.event_type] || wedding.event_type }}
                    </span>
                </div>
            </div>
            <div v-if="photos.length > 0" class="flex items-center gap-x-3">
                <button
                    @click="toggleSelectMode"
                    :class="[
                        selectMode ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-700 hover:bg-slate-200',
                        'inline-flex items-center gap-x-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors'
                    ]"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ selectMode ? 'Anulează' : 'Selectează' }}
                </button>
                <a
                    :href="isAdmin ? route('admin.events.download', wedding.id) : route('couple.download')"
                    class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descarcă Tot
                </a>
            </div>
        </template>

        <!-- Stats -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-600">
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                        </p>
                        <p class="text-sm text-slate-500">Data Evenimentului</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Bar -->
        <div v-if="selectMode" class="mb-4 rounded-2xl bg-violet-50 p-4 flex items-center justify-between">
            <div class="flex items-center gap-x-4">
                <span class="text-sm font-medium text-violet-900">
                    {{ selectedPhotos.size }} {{ selectedPhotos.size === 1 ? 'fotografie selectată' : 'fotografii selectate' }}
                </span>
                <button @click="selectAll" class="text-sm text-violet-600 hover:text-violet-700 font-medium">
                    Selectează tot
                </button>
                <button @click="deselectAll" class="text-sm text-violet-600 hover:text-violet-700 font-medium">
                    Deselectează tot
                </button>
            </div>
            <button
                @click="downloadSelected"
                :disabled="selectedPhotos.size === 0 || downloading"
                class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 transition-all"
            >
                <svg v-if="downloading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descarcă Selectate
            </button>
        </div>

        <!-- Photo Gallery -->
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">{{ wedding.couple_name }}</h2>
                <div class="flex items-center gap-x-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Caută după nume..."
                            class="pl-9 pr-4 py-2 rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500 w-48"
                        />
                    </div>
                    <div class="flex items-center gap-x-2">
                        <span class="text-sm text-slate-500">Sortare:</span>
                        <select
                            v-model="sortBy"
                            class="rounded-lg border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                        >
                            <option value="date">Data încărcării</option>
                            <option value="name">Nume încărcător</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div v-if="sortedPhotos.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    <div
                        v-for="(photo, index) in sortedPhotos"
                        :key="photo.id"
                        @click="selectMode ? togglePhotoSelection(photo.id, $event) : openLightbox(photo, index)"
                        :class="[
                            'group relative aspect-square cursor-pointer overflow-hidden rounded-xl bg-slate-100',
                            selectMode && selectedPhotos.has(photo.id) ? 'ring-4 ring-violet-500' : ''
                        ]"
                    >
                        <img
                            :src="photo.url || `/storage/${photo.path}`"
                            :alt="photo.original_name"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                        />
                        <!-- Checkbox overlay for select mode -->
                        <div
                            v-if="selectMode"
                            class="absolute top-2 left-2 z-10"
                            @click.stop="togglePhotoSelection(photo.id, $event)"
                        >
                            <div
                                :class="[
                                    'h-6 w-6 rounded-full border-2 flex items-center justify-center transition-colors',
                                    selectedPhotos.has(photo.id)
                                        ? 'bg-violet-500 border-violet-500'
                                        : 'bg-white/80 border-slate-300 hover:border-violet-400'
                                ]"
                            >
                                <svg v-if="selectedPhotos.has(photo.id)" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
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
                        Nu există fotografii încărcate pentru acest eveniment.
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

                <!-- Photo counter -->
                <div class="absolute left-4 top-4 rounded-full bg-white/10 px-4 py-2 text-white text-sm font-medium">
                    {{ selectedIndex + 1 }} / {{ sortedPhotos.length }}
                </div>

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
                        :src="selectedPhoto.url || `/storage/${selectedPhoto.path}`"
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
    </component>
</template>
