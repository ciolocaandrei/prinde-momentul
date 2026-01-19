<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Autentificare" />

    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-slate-700/30 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-rose-500/5 rounded-full blur-2xl -translate-x-1/2 -translate-y-1/2"></div>
            </div>

            <!-- Content -->
            <div class="relative flex flex-col justify-between p-12 w-full">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-x-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                        <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white">Prinde Momentul</span>
                </Link>

                <!-- Main content -->
                <div class="max-w-md">
                    <h1 class="text-4xl font-bold text-white leading-tight">
                        Bine ai venit inapoi!
                    </h1>
                    <p class="mt-4 text-lg text-white/80 leading-relaxed">
                        Accesează galeria de fotografii si redescoperă momentele speciale de la evenimentul tau.
                    </p>

                    <!-- Features list -->
                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-x-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-white/90">Fotografii in timp real de la invitati</span>
                        </div>
                        <div class="flex items-center gap-x-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <span class="text-white/90">Descarca toate pozele</span>
                        </div>
                        <div class="flex items-center gap-x-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </div>
                            <span class="text-white/90">Distribuie linkul de upload usor</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom decoration -->
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 ring-2 ring-white/20"></div>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-400 ring-2 ring-white/20"></div>
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-400 ring-2 ring-white/20"></div>
                    </div>
                    <p class="text-sm text-white/70">
                        Mii de amintiri capturate de invitati
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24 bg-slate-50">
            <div class="mx-auto w-full max-w-sm">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-10">
                    <Link href="/" class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-slate-900">
                            Prinde Momentul
                        </span>
                    </Link>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-900">
                        Autentificare
                    </h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Introdu credentialele primite pentru a accesa galeria
                    </p>
                </div>

                <div v-if="status" class="mt-6 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700 ring-1 ring-emerald-200">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">
                            Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            class="mt-2 block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-slate-900 sm:text-sm"
                            placeholder="email@exemplu.ro"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">
                            Parola
                        </label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            class="mt-2 block w-full rounded-xl border-0 bg-white px-4 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-slate-900 sm:text-sm"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                            />
                            <span class="ms-2 text-sm text-slate-600">Tine-ma minte</span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-rose-500 hover:text-rose-600"
                        >
                            Ai uitat parola?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="relative w-full rounded-xl bg-slate-900 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/30 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Se incarca...
                        </span>
                        <span v-else>Autentificare</span>
                    </button>
                </form>

                <!-- Back to home -->
                <div class="mt-10 text-center">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-x-2 text-sm text-slate-500 hover:text-slate-700 transition-colors"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Inapoi la pagina principala
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
