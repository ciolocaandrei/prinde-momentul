<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    eventTypes: Object,
});

const form = useForm({
    couple_name: '',
    event_type: 'nunta',
    event_date: '',
    email: '',
    password: '',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.events.store'));
};

const generatePassword = () => {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let password = '';
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    form.password = password;
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

                <!-- Couple Account -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Cont Utilizator</h2>
                        <p class="mt-1 text-sm text-slate-500">Credențiale de autentificare pentru accesul la galeria foto.</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Adresă Email</label>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                placeholder="email@exemplu.com"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                required
                            />
                            <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Parolă</label>
                            <div class="mt-2 flex gap-x-3">
                                <input
                                    id="password"
                                    type="text"
                                    v-model="form.password"
                                    placeholder="Minim 8 caractere"
                                    class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                    required
                                />
                                <button
                                    type="button"
                                    @click="generatePassword"
                                    class="inline-flex items-center gap-x-1.5 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-colors whitespace-nowrap"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Generează
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
                            <p class="mt-2 text-xs text-slate-500">Transmite aceste credențiale utilizatorului pentru autentificare.</p>
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
