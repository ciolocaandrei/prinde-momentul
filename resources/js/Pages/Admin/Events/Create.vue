<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    eventTypes: Object,
    prefill: Object,
});

const form = useForm({
    couple_name: props.prefill?.couple_name || '',
    event_type: props.prefill?.event_type || 'nunta',
    event_date: props.prefill?.event_date || '',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.events.store'));
};
</script>

<template>
    <Head title="Creare Eveniment" />

    <AdminLayout>
        <template #header>
            <div>
                <nav class="flex items-center gap-x-2 text-sm">
                    <Link :href="route('admin.events.index')" class="text-slate-500 hover:text-slate-700 transition-colors">Evenimente</Link>
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-medium">Creare</span>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">Eveniment Nou</h1>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <!-- Prefill notification -->
            <div v-if="prefill" class="mb-6 rounded-2xl bg-violet-50 ring-1 ring-violet-200 p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-violet-800">
                            Date precompletate din precomanda #{{ prefill.from_preorder }}
                        </p>
                        <p class="mt-1 text-sm text-violet-700">
                            Verifica si completeaza campurile lipsa inainte de a crea evenimentul.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Event Details -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Detalii Eveniment</h2>
                        <p class="mt-1 text-sm text-slate-500">Informații de bază despre eveniment.</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <div>
                            <label for="couple_name" class="block text-sm font-medium text-slate-700">Nume</label>
                            <input
                                id="couple_name"
                                type="text"
                                v-model="form.couple_name"
                                placeholder="ex: Ana & Mihai"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            />
                            <p v-if="form.errors.couple_name" class="mt-2 text-sm text-red-600">{{ form.errors.couple_name }}</p>
                        </div>

                        <div>
                            <label for="event_type" class="block text-sm font-medium text-slate-700">Tip Eveniment</label>
                            <select
                                id="event_type"
                                v-model="form.event_type"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            >
                                <option v-for="(label, value) in eventTypes" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </select>
                            <p v-if="form.errors.event_type" class="mt-2 text-sm text-red-600">{{ form.errors.event_type }}</p>
                        </div>

                        <div>
                            <label for="event_date" class="block text-sm font-medium text-slate-700">Data Evenimentului</label>
                            <input
                                id="event_date"
                                type="date"
                                v-model="form.event_date"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            />
                            <p v-if="form.errors.event_date" class="mt-2 text-sm text-red-600">{{ form.errors.event_date }}</p>
                        </div>

                        <div class="flex items-center gap-x-3">
                            <input
                                id="is_active"
                                type="checkbox"
                                v-model="form.is_active"
                                class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                            />
                            <label for="is_active" class="text-sm font-medium text-slate-700">
                                Activează upload-ul de fotografii imediat
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-x-4 pt-4">
                    <Link
                        :href="route('admin.events.index')"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                    >
                        Anulează
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 transition-all"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creează Eveniment
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
