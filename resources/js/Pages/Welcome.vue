<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
});

const mobileMenuOpen = ref(false);
</script>

<template>
    <Head title="Prinde Momentul - Fotografii de la Evenimente" />

    <div class="min-h-screen bg-slate-50">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600 to-purple-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">
                            Prinde Momentul
                        </span>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden sm:flex items-center gap-x-4">
                        <Link
                            :href="route('preorder.create')"
                            class="text-sm font-medium text-slate-600 hover:text-violet-600 transition-colors"
                        >
                            Comanda acum
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                        >
                            Dashboard
                        </Link>
                        <Link
                            v-else-if="canLogin"
                            :href="route('login')"
                            class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                        >
                            Autentificare
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="sm:hidden inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                    >
                        <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-1"
            >
                <div v-if="mobileMenuOpen" class="sm:hidden bg-white/95 backdrop-blur-lg border-t border-slate-200/50">
                    <div class="px-4 py-4 space-y-3">
                        <Link
                            :href="route('preorder.create')"
                            class="block rounded-xl bg-slate-50 px-4 py-3 text-base font-medium text-slate-700 hover:bg-slate-100"
                        >
                            Comanda acum
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="block rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-3 text-base font-semibold text-white text-center"
                        >
                            Dashboard
                        </Link>
                        <Link
                            v-else-if="canLogin"
                            :href="route('login')"
                            class="block rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-3 text-base font-semibold text-white text-center"
                        >
                            Autentificare
                        </Link>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] rounded-full bg-gradient-to-br from-violet-400/30 to-purple-400/30 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] rounded-full bg-gradient-to-br from-pink-400/20 to-rose-400/20 blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="inline-flex items-center gap-x-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-medium text-violet-700 mb-8">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Platforma ideala pentru evenimente
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 tracking-tight">
                        Aduna toate fotografiile
                        <span class="block mt-2 bg-gradient-to-r from-violet-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                            de la evenimentul tau
                        </span>
                    </h1>

                    <p class="mt-8 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                        Invitații tai pot incarca fotografii instant, iar tu le poti vedea in timp real.
                        Niciun moment prețios nu va fi pierdut.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            :href="route('preorder.create')"
                            class="inline-flex items-center gap-x-2 rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-violet-500/30 hover:from-violet-700 hover:to-purple-700 hover:shadow-violet-500/40 transition-all transform hover:scale-105"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Comanda acum
                        </Link>
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="inline-flex items-center gap-x-2 rounded-2xl bg-white px-8 py-4 text-base font-semibold text-violet-600 shadow-lg ring-1 ring-slate-200 hover:bg-slate-50 transition-all"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Accesează Contul
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="inline-flex items-center gap-x-2 rounded-2xl bg-white px-8 py-4 text-base font-semibold text-violet-600 shadow-lg ring-1 ring-slate-200 hover:bg-slate-50 transition-all"
                        >
                            Mergi la Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Floating images decoration -->
                <div class="mt-20 relative">
                    <div class="grid grid-cols-3 gap-4 max-w-4xl mx-auto">
                        <div class="rounded-2xl bg-gradient-to-br from-violet-100 to-purple-100 aspect-[4/5] shadow-lg transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="h-full w-full rounded-2xl bg-gradient-to-br from-violet-500/10 to-purple-500/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-pink-100 to-rose-100 aspect-[4/5] shadow-lg transform rotate-2 hover:rotate-0 transition-transform duration-500 translate-y-8">
                            <div class="h-full w-full rounded-2xl bg-gradient-to-br from-pink-500/10 to-rose-500/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 aspect-[4/5] shadow-lg transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                            <div class="h-full w-full rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 flex items-center justify-center">
                                <svg class="h-16 w-16 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 lg:py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">
                        Cum functioneaza
                    </h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Simplu, rapid si eficient. In doar cateva minute ai toate fotografiile de la eveniment.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="relative group">
                        <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 opacity-0 group-hover:opacity-10 transition-opacity blur"></div>
                        <div class="relative rounded-2xl bg-slate-50 p-8 ring-1 ring-slate-200/50 hover:ring-violet-200 transition-all">
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg shadow-violet-500/30">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">Distribuie Linkul</h3>
                            <p class="mt-3 text-slate-600 leading-relaxed">
                                Primești un link unic pe care il trimiți invitaților. Pot accesa pagina de upload direct de pe telefon.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative group">
                        <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-pink-600 to-rose-600 opacity-0 group-hover:opacity-10 transition-opacity blur"></div>
                        <div class="relative rounded-2xl bg-slate-50 p-8 ring-1 ring-slate-200/50 hover:ring-pink-200 transition-all">
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 shadow-lg shadow-pink-500/30">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">Invitații Încarcă</h3>
                            <p class="mt-3 text-slate-600 leading-relaxed">
                                Fara cont, fara aplicație. Invitații aleg fotografiile și le încarcă instant cu numele lor.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative group">
                        <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 opacity-0 group-hover:opacity-10 transition-opacity blur"></div>
                        <div class="relative rounded-2xl bg-slate-50 p-8 ring-1 ring-slate-200/50 hover:ring-emerald-200 transition-all">
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/30">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">Vezi in Timp Real</h3>
                            <p class="mt-3 text-slate-600 leading-relaxed">
                                Fotografiile apar automat în galeria ta. Le poți descarca toate oricând ca arhiva ZIP.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Event Types Section -->
        <section class="py-20 lg:py-32 bg-gradient-to-br from-slate-50 to-violet-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">
                        Pentru orice tip de eveniment
                    </h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Indiferent de ocazie, Prinde Momentul te ajuta sa aduni amintirile.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    <!-- Nunta -->
                    <div class="group cursor-pointer">
                        <div class="aspect-square rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/50 flex flex-col items-center justify-center gap-4 hover:shadow-lg hover:ring-violet-200 transition-all">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-100 to-rose-100 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Nunta</span>
                        </div>
                    </div>

                    <!-- Botez -->
                    <div class="group cursor-pointer">
                        <div class="aspect-square rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/50 flex flex-col items-center justify-center gap-4 hover:shadow-lg hover:ring-violet-200 transition-all">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-100 to-blue-100 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Botez</span>
                        </div>
                    </div>

                    <!-- Majorat -->
                    <div class="group cursor-pointer">
                        <div class="aspect-square rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/50 flex flex-col items-center justify-center gap-4 hover:shadow-lg hover:ring-violet-200 transition-all">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-100 to-violet-100 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Majorat</span>
                        </div>
                    </div>

                    <!-- Zi de Nastere -->
                    <div class="group cursor-pointer">
                        <div class="aspect-square rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/50 flex flex-col items-center justify-center gap-4 hover:shadow-lg hover:ring-violet-200 transition-all">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Zi de Nastere</span>
                        </div>
                    </div>

                    <!-- Altele -->
                    <div class="group cursor-pointer">
                        <div class="aspect-square rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/50 flex flex-col items-center justify-center gap-4 hover:shadow-lg hover:ring-violet-200 transition-all">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-100 to-teal-100 group-hover:scale-110 transition-transform">
                                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Altele</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative rounded-3xl bg-gradient-to-r from-violet-600 via-purple-600 to-pink-600 p-12 lg:p-20 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute inset-0 opacity-30">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative text-center">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                            Gata sa prinzi momentele speciale?
                        </h2>
                        <p class="mt-6 text-lg text-white/80 max-w-2xl mx-auto">
                            Fa o precomanda acum si te vom contacta pentru a configura evenimentul tau.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                            <Link
                                :href="route('preorder.create')"
                                class="inline-flex items-center gap-x-2 rounded-2xl bg-white px-8 py-4 text-base font-semibold text-violet-600 shadow-xl hover:bg-violet-50 transition-all transform hover:scale-105"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Comanda acum
                            </Link>
                            <Link
                                v-if="canLogin && $page.props.auth.user"
                                :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                                class="inline-flex items-center gap-x-2 rounded-2xl bg-white/20 backdrop-blur px-8 py-4 text-base font-semibold text-white ring-1 ring-white/30 hover:bg-white/30 transition-all"
                            >
                                Mergi la Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-600 to-purple-600">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-700">Prinde Momentul</span>
                    </div>
                    <p class="text-sm text-slate-500">
                        &copy; {{ new Date().getFullYear() }} Prinde Momentul. Toate drepturile rezervate.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
