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

    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl bg-slate-900">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-lg sm:text-xl font-semibold text-slate-900 tracking-tight">
                            Prinde Momentul
                        </span>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-x-8">
                        <Link
                            :href="route('preorder.create')"
                            class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            Comanda
                        </Link>
                        <Link
                            href="/termeni"
                            class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            Termeni
                        </Link>
                        <Link
                            href="/gdpr"
                            class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"
                        >
                            GDPR
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="inline-flex items-center gap-x-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition-colors"
                        >
                            Dashboard
                        </Link>
                        <Link
                            v-else-if="canLogin"
                            :href="route('login')"
                            class="inline-flex items-center gap-x-2 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-800 transition-colors"
                        >
                            Autentificare
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden inline-flex items-center justify-center rounded-lg p-2.5 text-slate-600 hover:bg-slate-100 transition-colors"
                    >
                        <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-1"
            >
                <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t border-slate-100">
                    <div class="px-4 py-4 space-y-1">
                        <Link
                            :href="route('preorder.create')"
                            class="block rounded-lg px-4 py-3 text-base font-medium text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Comanda acum
                        </Link>
                        <Link
                            href="/termeni"
                            class="block rounded-lg px-4 py-3 text-base font-medium text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Termeni si Conditii
                        </Link>
                        <Link
                            href="/gdpr"
                            class="block rounded-lg px-4 py-3 text-base font-medium text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Politica GDPR
                        </Link>
                        <div class="pt-3">
                            <Link
                                v-if="canLogin && $page.props.auth.user"
                                :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                                class="block w-full text-center rounded-full bg-slate-900 px-5 py-3 text-base font-medium text-white"
                            >
                                Dashboard
                            </Link>
                            <Link
                                v-else-if="canLogin"
                                :href="route('login')"
                                class="block w-full text-center rounded-full bg-slate-900 px-5 py-3 text-base font-medium text-white"
                            >
                                Autentificare
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-28 pb-16 sm:pt-36 sm:pb-24 lg:pt-44 lg:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-4xl mx-auto">
                    <p class="text-sm font-semibold tracking-widest text-rose-500 uppercase mb-4 sm:mb-6">
                        Platforma pentru evenimente
                    </p>

                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 tracking-tight leading-[1.1]">
                        Toate fotografiile
                        <span class="block text-slate-400">
                            intr-un singur loc
                        </span>
                    </h1>

                    <p class="mt-6 sm:mt-8 text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                        Invitații tai pot incarca fotografii instant de pe telefon. Tu le vezi in timp real, fara sa pierzi niciun moment special.
                    </p>

                    <div class="mt-10 sm:mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            :href="route('preorder.create')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-full bg-slate-900 px-8 py-4 text-base font-semibold text-white hover:bg-slate-800 transition-all"
                        >
                            Incepe acum
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                        <Link
                            v-if="canLogin && !$page.props.auth.user"
                            :href="route('login')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-full bg-white px-8 py-4 text-base font-semibold text-slate-700 ring-1 ring-slate-200 hover:ring-slate-300 hover:bg-slate-50 transition-all"
                        >
                            Accesează Contul
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-full bg-white px-8 py-4 text-base font-semibold text-slate-700 ring-1 ring-slate-200 hover:ring-slate-300 hover:bg-slate-50 transition-all"
                        >
                            Mergi la Dashboard
                        </Link>
                    </div>
                </div>

                <!-- Visual Element -->
                <div class="mt-16 sm:mt-20 lg:mt-24 relative">
                    <div class="max-w-5xl mx-auto">
                        <div class="relative rounded-2xl sm:rounded-3xl bg-gradient-to-br from-slate-100 to-slate-50 p-4 sm:p-6 lg:p-8 ring-1 ring-slate-200/50">
                            <div class="grid grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                                <div class="aspect-[4/5] rounded-xl sm:rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
                                    <div class="h-full w-full bg-gradient-to-br from-rose-50 to-orange-50 flex items-center justify-center">
                                        <svg class="h-8 w-8 sm:h-12 sm:w-12 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="aspect-[4/5] rounded-xl sm:rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden -mt-4 sm:-mt-8">
                                    <div class="h-full w-full bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center">
                                        <svg class="h-8 w-8 sm:h-12 sm:w-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="aspect-[4/5] rounded-xl sm:rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
                                    <div class="h-full w-full bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center">
                                        <svg class="h-8 w-8 sm:h-12 sm:w-12 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating badges -->
                            <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 bg-white rounded-full px-3 py-1.5 sm:px-4 sm:py-2 shadow-lg ring-1 ring-slate-100">
                                <span class="text-xs sm:text-sm font-semibold text-slate-900">Live Updates</span>
                            </div>
                            <div class="absolute -bottom-3 -left-3 sm:-bottom-4 sm:-left-4 bg-slate-900 rounded-full px-3 py-1.5 sm:px-4 sm:py-2 shadow-lg">
                                <span class="text-xs sm:text-sm font-semibold text-white">Fara aplicatie</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 sm:py-28 lg:py-32 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
                    <p class="text-sm font-semibold tracking-widest text-rose-500 uppercase mb-4">
                        Simplu si rapid
                    </p>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 tracking-tight">
                        Cum functioneaza
                    </h2>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-10">
                    <!-- Feature 1 -->
                    <div class="relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10 ring-1 ring-slate-100 hover:shadow-lg transition-shadow">
                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-slate-900 mb-6">
                            <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-rose-500 mb-2 block">01</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3">Distribuie Linkul</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Primești un link unic pe care il trimiți invitaților prin WhatsApp, SMS sau email.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10 ring-1 ring-slate-100 hover:shadow-lg transition-shadow">
                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-slate-900 mb-6">
                            <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-rose-500 mb-2 block">02</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3">Invitații Încarcă</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Fara cont, fara aplicație. Invitații aleg fotografiile direct din galerie si le încarcă instant.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 lg:p-10 ring-1 ring-slate-100 hover:shadow-lg transition-shadow sm:col-span-2 lg:col-span-1">
                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-slate-900 mb-6">
                            <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-rose-500 mb-2 block">03</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-3">Vezi Totul Live</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Fotografiile apar automat în galeria ta. Le poți descarca toate oricând ca arhiva ZIP.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Event Types Section -->
        <section class="py-20 sm:py-28 lg:py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
                    <p class="text-sm font-semibold tracking-widest text-rose-500 uppercase mb-4">
                        Versatil
                    </p>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-slate-900 tracking-tight">
                        Pentru orice eveniment
                    </h2>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 sm:gap-6">
                    <div class="group">
                        <div class="aspect-square rounded-2xl sm:rounded-3xl bg-slate-50 p-4 sm:p-6 flex flex-col items-center justify-center gap-3 sm:gap-4 ring-1 ring-slate-100 hover:ring-slate-200 hover:bg-white transition-all">
                            <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-rose-50 group-hover:scale-105 transition-transform">
                                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-slate-700">Nunta</span>
                        </div>
                    </div>

                    <div class="group">
                        <div class="aspect-square rounded-2xl sm:rounded-3xl bg-slate-50 p-4 sm:p-6 flex flex-col items-center justify-center gap-3 sm:gap-4 ring-1 ring-slate-100 hover:ring-slate-200 hover:bg-white transition-all">
                            <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-sky-50 group-hover:scale-105 transition-transform">
                                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-slate-700">Botez</span>
                        </div>
                    </div>

                    <div class="group">
                        <div class="aspect-square rounded-2xl sm:rounded-3xl bg-slate-50 p-4 sm:p-6 flex flex-col items-center justify-center gap-3 sm:gap-4 ring-1 ring-slate-100 hover:ring-slate-200 hover:bg-white transition-all">
                            <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-amber-50 group-hover:scale-105 transition-transform">
                                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-slate-700">Majorat</span>
                        </div>
                    </div>

                    <div class="group">
                        <div class="aspect-square rounded-2xl sm:rounded-3xl bg-slate-50 p-4 sm:p-6 flex flex-col items-center justify-center gap-3 sm:gap-4 ring-1 ring-slate-100 hover:ring-slate-200 hover:bg-white transition-all">
                            <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-orange-50 group-hover:scale-105 transition-transform">
                                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-slate-700 text-center">Aniversare</span>
                        </div>
                    </div>

                    <div class="group col-span-2 sm:col-span-1">
                        <div class="aspect-auto sm:aspect-square rounded-2xl sm:rounded-3xl bg-slate-50 p-4 sm:p-6 flex flex-col items-center justify-center gap-3 sm:gap-4 ring-1 ring-slate-100 hover:ring-slate-200 hover:bg-white transition-all">
                            <div class="flex h-12 w-12 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-emerald-50 group-hover:scale-105 transition-transform">
                                <svg class="h-6 w-6 sm:h-8 sm:w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base font-semibold text-slate-700">Corporate</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 sm:py-28 lg:py-32 bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-tight leading-tight">
                        Gata sa colectezi amintiri?
                    </h2>
                    <p class="mt-6 text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto">
                        Fa o precomanda acum si te vom contacta pentru a configura totul pentru evenimentul tau.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            :href="route('preorder.create')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-full bg-white px-8 py-4 text-base font-semibold text-slate-900 hover:bg-slate-100 transition-colors"
                        >
                            Comanda acum
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                        <Link
                            v-if="canLogin && $page.props.auth.user"
                            :href="$page.props.auth.user.role === 'admin' ? route('admin.dashboard') : route('dashboard')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-full bg-slate-800 px-8 py-4 text-base font-semibold text-white ring-1 ring-slate-700 hover:bg-slate-700 transition-colors"
                        >
                            Dashboard
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 sm:py-16 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center gap-8">
                    <!-- Logo -->
                    <div class="flex items-center gap-x-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-slate-900">Prinde Momentul</span>
                    </div>

                    <!-- Links -->
                    <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 text-sm">
                        <Link href="/termeni" class="text-slate-500 hover:text-slate-900 transition-colors">
                            Termeni si Conditii
                        </Link>
                        <Link href="/gdpr" class="text-slate-500 hover:text-slate-900 transition-colors">
                            Politica GDPR
                        </Link>
                        <Link :href="route('preorder.create')" class="text-slate-500 hover:text-slate-900 transition-colors">
                            Contact
                        </Link>
                    </div>

                    <!-- Copyright -->
                    <p class="text-sm text-slate-400 text-center">
                        &copy; {{ new Date().getFullYear() }} Prinde Momentul. Toate drepturile rezervate.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
