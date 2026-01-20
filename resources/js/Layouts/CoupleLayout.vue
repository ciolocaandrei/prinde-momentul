<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-slate-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <!-- Logo -->
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <div class="flex items-center gap-x-2">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-purple-600">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-lg font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">Prinde Momentul</span>
                            </div>
                        </div>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-1">
                            <Link
                                :href="route('dashboard')"
                                class="bg-violet-50 text-violet-700 rounded-lg px-4 py-2 text-sm font-medium"
                            >
                                Fotografii
                            </Link>
                        </div>
                    </div>

                    <!-- Right side - User menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center gap-x-4">
                        <div class="flex items-center gap-x-3">
                            <div class="text-right">
                                <p class="text-sm font-medium text-slate-900">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-500">{{ $page.props.auth.user.email }}</p>
                            </div>
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-sm font-medium text-white">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>
                        <div class="h-6 w-px bg-slate-200" />
                        <Link
                            :href="route('profile.edit')"
                            class="text-slate-500 hover:text-slate-700 transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-slate-500 hover:text-red-600 transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700"
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
                <div v-if="mobileMenuOpen" class="sm:hidden border-t border-slate-200">
                    <div class="space-y-1 px-3 py-3">
                        <Link
                            :href="route('dashboard')"
                            class="bg-violet-50 text-violet-700 block rounded-lg px-3 py-2.5 text-base font-medium"
                        >
                            Fotografii
                        </Link>
                    </div>
                    <div class="border-t border-slate-200 px-4 py-4">
                        <div class="flex items-center gap-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-sm font-medium text-white">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-500">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <Link
                                :href="route('profile.edit')"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-200"
                            >
                                Setări
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-200"
                            >
                                Deconectare
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Page Header -->
        <header class="bg-white border-b border-slate-200" v-if="$slots.header">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <slot name="header" />
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
