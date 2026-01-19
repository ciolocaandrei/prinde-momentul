<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Precomanda Trimisa - Prinde Momentul" />

    <div class="min-h-screen bg-slate-50 flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-slate-200/50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <Link href="/" class="flex items-center gap-x-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-900">Prinde Momentul</span>
                </Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full text-center">
                <!-- Success Icon -->
                <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-8">
                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Success Message -->
                <h1 class="text-3xl font-bold text-slate-900 mb-4">
                    Precomanda a fost trimisa!
                </h1>
                <p class="text-lg text-slate-600 mb-8">
                    Multumim pentru interesul acordat! Te vom contacta in cel mai scurt timp posibil pentru a discuta detaliile.
                </p>

                <!-- What's Next -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 p-6 mb-8 text-left">
                    <h2 class="text-sm font-semibold text-slate-900 mb-4">Ce urmeaza?</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">1</div>
                            <p class="text-sm text-slate-600">Vei primi un email de confirmare cu detaliile precomenzii.</p>
                        </li>
                        <li class="flex items-start gap-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">2</div>
                            <p class="text-sm text-slate-600">Echipa noastra te va contacta pentru a stabili detaliile finale.</p>
                        </li>
                        <li class="flex items-start gap-x-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">3</div>
                            <p class="text-sm text-slate-600">Vom crea contul tau si vei primi credentialele de acces.</p>
                        </li>
                    </ul>
                </div>

                <!-- Back Button -->
                <Link
                    href="/"
                    class="inline-flex items-center gap-x-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 hover:bg-slate-800 transition-all"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Inapoi la pagina principala
                </Link>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white/50 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
                <p>&copy; {{ new Date().getFullYear() }} Prinde Momentul. Toate drepturile rezervate.</p>
            </div>
        </footer>
    </div>
</template>
