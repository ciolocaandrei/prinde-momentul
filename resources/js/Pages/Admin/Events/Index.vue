<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

const props = defineProps({
    weddings: Object,
    eventTypes: Object,
    filters: Object,
});

const eventTypeLabels = {
    nunta: 'Nuntă',
    majorat: 'Majorat',
    zi_de_nastere: 'Zi de Naștere',
    botez: 'Botez',
    altele: 'Alt Eveniment',
};

const search = ref(props.filters?.search || '');
const eventType = ref(props.filters?.event_type || '');
const status = ref(props.filters?.status || '');
const sort = ref(props.filters?.sort || 'created_at');
const direction = ref(props.filters?.direction || 'desc');

const applyFilters = () => {
    router.get(route('admin.events.index'), {
        search: search.value || undefined,
        event_type: eventType.value || undefined,
        status: status.value || undefined,
        sort: sort.value,
        direction: direction.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const debouncedSearch = useDebounceFn(applyFilters, 300);

watch(search, debouncedSearch);
watch([eventType, status, sort, direction], applyFilters);

const clearFilters = () => {
    search.value = '';
    eventType.value = '';
    status.value = '';
    sort.value = 'created_at';
    direction.value = 'desc';
    applyFilters();
};

const hasActiveFilters = () => {
    return search.value || eventType.value || status.value || sort.value !== 'created_at' || direction.value !== 'desc';
};

const deleteWedding = (wedding) => {
    if (confirm(`Ești sigur că vrei să ștergi "${wedding.couple_name}"? Toate fotografiile și contul asociat vor fi șterse permanent.`)) {
        router.delete(route('admin.events.destroy', wedding.id));
    }
};
</script>

<template>
    <Head title="Gestionare Evenimente" />

    <AdminLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Evenimente</h1>
                <p class="mt-1 text-sm text-slate-500">Gestionează toate evenimentele și conturile</p>
            </div>
            <Link
                :href="route('admin.events.create')"
                class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Eveniment Nou
            </Link>
        </template>

        <!-- Filters -->
        <div class="mb-6 rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Caută după nume sau email..."
                            class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                        />
                    </div>
                </div>

                <!-- Event Type -->
                <select
                    v-model="eventType"
                    class="rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                >
                    <option value="">Toate tipurile</option>
                    <option v-for="(label, value) in eventTypeLabels" :key="value" :value="value">
                        {{ label }}
                    </option>
                </select>

                <!-- Status -->
                <select
                    v-model="status"
                    class="rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                >
                    <option value="">Orice status</option>
                    <option value="active">Activ</option>
                    <option value="inactive">Inactiv</option>
                </select>

                <!-- Sort -->
                <select
                    v-model="sort"
                    class="rounded-xl border-slate-300 text-sm focus:border-violet-500 focus:ring-violet-500"
                >
                    <option value="created_at">Data creare</option>
                    <option value="event_date">Data eveniment</option>
                    <option value="couple_name">Nume</option>
                </select>

                <!-- Direction -->
                <button
                    @click="direction = direction === 'desc' ? 'asc' : 'desc'"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-300 text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                    :title="direction === 'desc' ? 'Descrescător' : 'Crescător'"
                >
                    <svg v-if="direction === 'desc'" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                    <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                    </svg>
                </button>

                <!-- Clear Filters -->
                <button
                    v-if="hasActiveFilters()"
                    @click="clearFilters"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Resetează
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Nume
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Tip
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Data
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Fotografii
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>
                        <th scope="col" class="relative py-4 pl-3 pr-6">
                            <span class="sr-only">Acțiuni</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <tr v-for="wedding in weddings.data" :key="wedding.id" class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-5 pl-6 pr-3">
                            <div class="flex items-center gap-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 text-white text-sm font-bold">
                                    {{ wedding.couple_name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ wedding.couple_name }}</div>
                                    <div class="text-sm text-slate-500">{{ wedding.user.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                {{ eventTypeLabels[wedding.event_type] || wedding.event_type }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <div class="flex items-center gap-x-2 text-sm text-slate-700">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <div class="flex items-center gap-x-2 text-sm text-slate-700">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ wedding.photos_count }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <span
                                :class="[
                                    wedding.is_active
                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                                        : 'bg-slate-100 text-slate-600 ring-slate-500/10',
                                    'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset'
                                ]"
                            >
                                <span :class="[wedding.is_active ? 'bg-emerald-500' : 'bg-slate-400', 'mr-1.5 h-1.5 w-1.5 rounded-full']" />
                                {{ wedding.is_active ? 'Activ' : 'Inactiv' }}
                            </span>
                        </td>
                        <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-x-3">
                                <Link
                                    :href="route('admin.events.show', wedding.id)"
                                    class="text-violet-600 hover:text-violet-800 font-medium transition-colors"
                                >
                                    Vezi
                                </Link>
                                <button
                                    @click="deleteWedding(wedding)"
                                    class="text-red-500 hover:text-red-700 font-medium transition-colors"
                                >
                                    Șterge
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="weddings.data.length === 0">
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-full bg-slate-100">
                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-slate-900">Niciun eveniment încă</h3>
                            <p class="mt-2 text-sm text-slate-500">Începe prin a crea primul tău eveniment.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('admin.events.create')"
                                    class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Eveniment Nou
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="weddings.links && weddings.last_page > 1" class="flex items-center justify-between border-t border-slate-200 bg-white px-6 py-4">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="weddings.prev_page_url"
                        :href="weddings.prev_page_url"
                        class="relative inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Anterior
                    </Link>
                    <Link
                        v-if="weddings.next_page_url"
                        :href="weddings.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Următor
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-600">
                            Afișare <span class="font-semibold text-slate-900">{{ weddings.from }}</span> - <span class="font-semibold text-slate-900">{{ weddings.to }}</span> din <span class="font-semibold text-slate-900">{{ weddings.total }}</span> rezultate
                        </p>
                    </div>
                    <nav class="flex items-center gap-x-1">
                        <template v-for="link in weddings.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'bg-violet-600 text-white'
                                        : 'bg-white text-slate-700 hover:bg-slate-50 ring-1 ring-inset ring-slate-300',
                                    'relative inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-lg transition-colors'
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-400"
                                v-html="link.label"
                            />
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
