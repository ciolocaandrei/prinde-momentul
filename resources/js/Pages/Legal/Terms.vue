<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Termeni si Conditii" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-violet-50 to-purple-50">
        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <Link href="/" class="inline-flex items-center gap-x-2 text-sm text-violet-600 hover:text-violet-700 font-medium">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Inapoi
                </Link>
            </div>

            <div class="rounded-2xl bg-white p-8 sm:p-12 shadow-xl ring-1 ring-slate-900/5">
                <h1 class="text-3xl font-bold text-slate-900 mb-8">Termeni si Conditii de Utilizare</h1>

                <div class="prose prose-slate max-w-none">
                    <p class="text-slate-600 mb-6">
                        Ultima actualizare: {{ new Date().toLocaleDateString('ro-RO') }}
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">1. Acceptarea Termenilor</h2>
                    <p class="text-slate-600 mb-4">
                        Prin utilizarea platformei Prinde Momentul si incarcarea de fotografii sau videoclipuri, sunteti de acord cu acesti termeni si conditii. Daca nu sunteti de acord cu acesti termeni, va rugam sa nu utilizati serviciul.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">2. Descrierea Serviciului</h2>
                    <p class="text-slate-600 mb-4">
                        Prinde Momentul este o platforma care permite invitatilor la evenimente sa incarce fotografii si videoclipuri care vor fi disponibile organizatorilor evenimentului. Serviciul faciliteaza colectarea si partajarea amintirilor de la evenimente speciale.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">3. Continut Incarcat</h2>
                    <p class="text-slate-600 mb-4">Prin incarcarea de continut pe platforma noastra, declarati si garantati ca:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li>Detineti drepturile de autor sau aveti permisiunea de a incarca continutul respectiv</li>
                        <li>Continutul nu incalca drepturile de proprietate intelectuala ale altora</li>
                        <li>Continutul nu este ilegal, defaimator, obscen sau ofensator</li>
                        <li>Continutul nu contine virusi sau cod malitios</li>
                        <li>Aveti consimtamantul persoanelor care apar in fotografii pentru publicarea acestora</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">4. Licenta de Utilizare</h2>
                    <p class="text-slate-600 mb-4">
                        Prin incarcarea continutului, acordati platformei Prinde Momentul o licenta neexclusiva de a stoca, afisa si transmite continutul catre organizatorii evenimentului. Pastrați toate drepturile de autor asupra continutului dumneavoastra.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">5. Responsabilitati ale Utilizatorului</h2>
                    <p class="text-slate-600 mb-4">Utilizatorii sunt responsabili pentru:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li>Asigurarea ca au dreptul de a incarca continutul</li>
                        <li>Respectarea drepturilor altor persoane</li>
                        <li>Utilizarea serviciului in mod legal si etic</li>
                        <li>Furnizarea de informatii corecte</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">6. Continut Interzis</h2>
                    <p class="text-slate-600 mb-4">Este strict interzisa incarcarea de continut care:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li>Este ilegal sau promoveaza activitati ilegale</li>
                        <li>Este pornografic sau cu caracter sexual explicit</li>
                        <li>Promoveaza violenta, ura sau discriminarea</li>
                        <li>Incalca drepturile de autor ale altora</li>
                        <li>Contine informatii false sau inselatoare</li>
                        <li>Contine date personale ale altora fara consimtamant</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">7. Limitarea Raspunderii</h2>
                    <p class="text-slate-600 mb-4">
                        Prinde Momentul nu este responsabil pentru continutul incarcat de utilizatori. Ne rezervam dreptul de a sterge orice continut care incalca acesti termeni, fara notificare prealabila.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">8. Disponibilitatea Serviciului</h2>
                    <p class="text-slate-600 mb-4">
                        Ne straduim sa mentinem serviciul disponibil, dar nu garantam disponibilitatea neintrerupta. Pot exista perioade de mentenanta sau intreruperi tehnice.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">9. Modificari ale Termenilor</h2>
                    <p class="text-slate-600 mb-4">
                        Ne rezervam dreptul de a modifica acesti termeni in orice moment. Continuarea utilizarii serviciului dupa modificari constituie acceptarea noilor termeni.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">10. Legea Aplicabila</h2>
                    <p class="text-slate-600 mb-4">
                        Acesti termeni sunt guvernati de legile din Romania. Orice disputa va fi solutionata de instantele competente din Romania.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">11. Contact</h2>
                    <p class="text-slate-600 mb-4">
                        Pentru intrebari despre acesti termeni, ne puteti contacta la:
                    </p>
                    <p class="text-slate-600 mb-4">
                        <strong>Email:</strong> contact@prindemomentul.ro
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
