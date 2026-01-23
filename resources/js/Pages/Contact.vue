<script setup>
import { Head, Link } from '@inertiajs/vue3';

const phone = '0746313619';
const whatsappLink = `https://wa.me/40746313619?text=${encodeURIComponent('Bună! Sunt interesat de serviciul Prinde Momentul pentru evenimentul meu.')}`;
const phoneLink = `tel:${phone}`;
</script>

<template>
    <Head>
        <title>Contact - Prinde Momentul</title>
        <meta name="description" content="Contactează-ne pentru orice întrebare despre serviciul de colectare fotografii pentru nuntă, botez sau majorat. WhatsApp sau telefon." />
    </Head>

    <div class="min-h-screen bg-gradient-to-br from-violet-50 via-white to-purple-50">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-slate-200/50 sticky top-0 z-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <Link href="/" class="flex items-center gap-x-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-600 to-purple-600 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">Prinde Momentul</span>
                    </Link>
                    <Link
                        :href="route('preorder.create')"
                        class="text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors"
                    >
                        Rezervă acum
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
            <!-- Hero -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-violet-100 mb-6">
                    <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                    Hai să vorbim!
                </h1>
                <p class="text-lg text-slate-600 max-w-xl mx-auto">
                    Ai întrebări despre serviciul nostru? Vrei să afli mai multe detalii? Suntem aici să te ajutăm.
                </p>
            </div>

            <!-- Contact Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
                <!-- WhatsApp -->
                <a
                    :href="whatsappLink"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 p-6 sm:p-8 text-white shadow-xl shadow-green-500/25 hover:shadow-2xl hover:shadow-green-500/30 transition-all hover:-translate-y-1"
                >
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold mb-2">WhatsApp</h2>
                        <p class="text-white/80 mb-4">Scrie-ne pe WhatsApp pentru un răspuns rapid</p>
                        <div class="inline-flex items-center gap-2 text-lg font-semibold">
                            {{ phone }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                    <!-- Decorative circles -->
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/10"></div>
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 rounded-full bg-white/10"></div>
                </a>

                <!-- Phone -->
                <a
                    :href="phoneLink"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 sm:p-8 text-white shadow-xl shadow-violet-500/25 hover:shadow-2xl hover:shadow-violet-500/30 transition-all hover:-translate-y-1"
                >
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold mb-2">Telefon</h2>
                        <p class="text-white/80 mb-4">Sună-ne direct pentru o discuție</p>
                        <div class="inline-flex items-center gap-2 text-lg font-semibold">
                            {{ phone }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                    <!-- Decorative circles -->
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/10"></div>
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 rounded-full bg-white/10"></div>
                </a>
            </div>

            <!-- Info Section -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm ring-1 ring-slate-900/5 mb-12">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Program de lucru</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Luni - Vineri</p>
                            <p class="text-slate-500">09:00 - 18:00</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900">Sâmbătă - Duminică</p>
                            <p class="text-slate-500">10:00 - 14:00</p>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">
                    Răspundem la mesajele WhatsApp și în afara programului, de obicei în câteva ore.
                </p>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <p class="text-slate-600 mb-4">Pregătit să rezervi pentru evenimentul tău?</p>
                <Link
                    :href="route('preorder.create')"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                >
                    Rezervă acum
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
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
