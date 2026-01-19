<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentWeddings: Array,
});

const eventTypeLabels = {
    nunta: 'Nuntă',
    majorat: 'Majorat',
    zi_de_nastere: 'Zi de Naștere',
    botez: 'Botez',
    altele: 'Alt Eveniment',
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AdminLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">Privire de ansamblu asupra platformei</p>
            </div>
            <Link
                :href="route('admin.events.create')"
                class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 hover:bg-slate-800 transition-all"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Eveniment Nou
            </Link>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.total_weddings }}</p>
                        <p class="text-sm text-slate-500">Total Evenimente</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.active_weddings }}</p>
                        <p class="text-sm text-slate-500">Evenimente Active</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-rose-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.total_photos }}</p>
                        <p class="text-sm text-slate-500">Total Fotografii</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
                <div class="flex items-center gap-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ stats.total_couples }}</p>
                        <p class="text-sm text-slate-500">Utilizatori Înregistrați</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="mt-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Evenimente Recente</h3>
                </div>
                <ul role="list" class="divide-y divide-slate-100">
                    <li
                        v-for="wedding in recentWeddings"
                        :key="wedding.id"
                        class="relative"
                    >
                        <Link
                            :href="route('admin.events.show', wedding.id)"
                            class="flex items-center justify-between gap-x-6 px-6 py-5 hover:bg-slate-50 transition-colors"
                        >
                            <div class="flex min-w-0 gap-x-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900 text-white font-bold text-lg">
                                    {{ wedding.couple_name.charAt(0) }}
                                </div>
                                <div class="min-w-0 flex-auto">
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ wedding.couple_name }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-x-3 text-xs text-slate-500">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                        {{ eventTypeLabels[wedding.event_type] || wedding.event_type }}
                                    </span>
                                    <span class="flex items-center gap-x-1">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-x-4">
                            <div class="hidden sm:flex sm:flex-col sm:items-end">
                                <p class="text-sm font-medium text-slate-700">{{ wedding.photos_count }} fotografii</p>
                                <span
                                    :class="[
                                        wedding.is_active
                                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                                            : 'bg-slate-100 text-slate-600 ring-slate-500/10',
                                        'mt-1 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset'
                                    ]"
                                >
                                    {{ wedding.is_active ? 'Activ' : 'Inactiv' }}
                                </span>
                            </div>
                            <svg class="h-5 w-5 flex-none text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        </Link>
                    </li>
                    <li v-if="recentWeddings.length === 0" class="px-6 py-16 text-center">
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
                                class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 hover:bg-slate-800 transition-all"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Eveniment Nou
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
