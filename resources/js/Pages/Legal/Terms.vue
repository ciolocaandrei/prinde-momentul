<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Termeni si Conditii" />

    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header class="bg-white border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    <Link href="/" class="flex items-center gap-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-slate-900">Prinde Momentul</span>
                    </Link>
                    <Link href="/" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
                        Inapoi acasa
                    </Link>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="mb-10">
                <p class="text-sm font-semibold tracking-widest text-rose-500 uppercase mb-3">Legal</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">Termeni si Conditii</h1>
                <p class="mt-4 text-slate-500">Ultima actualizare: {{ new Date().toLocaleDateString('ro-RO') }}</p>
            </div>

            <div class="prose prose-slate max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-p:text-slate-600 prose-p:leading-relaxed prose-li:text-slate-600">
                <h2>1. Acceptarea Termenilor</h2>
                <p>
                    Prin utilizarea platformei Prinde Momentul si incarcarea de fotografii sau videoclipuri, sunteti de acord cu acesti termeni si conditii. Daca nu sunteti de acord cu acesti termeni, va rugam sa nu utilizati serviciul.
                </p>

                <h2>2. Descrierea Serviciului</h2>
                <p>
                    Prinde Momentul este o platforma care permite invitatilor la evenimente sa incarce fotografii si videoclipuri care vor fi disponibile organizatorilor evenimentului. Serviciul faciliteaza colectarea si partajarea amintirilor de la evenimente speciale.
                </p>

                <h2>3. Continut Incarcat</h2>
                <p>Prin incarcarea de continut pe platforma noastra, declarati si garantati ca:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Detineti drepturile de autor sau aveti permisiunea de a incarca continutul respectiv</li>
                    <li>Continutul nu incalca drepturile de proprietate intelectuala ale altora</li>
                    <li>Continutul nu este ilegal, defaimator, obscen sau ofensator</li>
                    <li>Continutul nu contine virusi sau cod malitios</li>
                    <li>Aveti consimtamantul persoanelor care apar in fotografii pentru publicarea acestora</li>
                </ul>

                <h2>4. Licenta de Utilizare</h2>
                <p>
                    Prin incarcarea continutului, acordati platformei Prinde Momentul o licenta neexclusiva de a stoca, afisa si transmite continutul catre organizatorii evenimentului. Pastrati toate drepturile de autor asupra continutului dumneavoastra.
                </p>

                <h2>5. Responsabilitati ale Utilizatorului</h2>
                <p>Utilizatorii sunt responsabili pentru:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Asigurarea ca au dreptul de a incarca continutul</li>
                    <li>Respectarea drepturilor altor persoane</li>
                    <li>Utilizarea serviciului in mod legal si etic</li>
                    <li>Furnizarea de informatii corecte</li>
                </ul>

                <h2>6. Continut Interzis</h2>
                <p>Este strict interzisa incarcarea de continut care:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Este ilegal sau promoveaza activitati ilegale</li>
                    <li>Este pornografic sau cu caracter sexual explicit</li>
                    <li>Promoveaza violenta, ura sau discriminarea</li>
                    <li>Incalca drepturile de autor ale altora</li>
                    <li>Contine informatii false sau inselatoare</li>
                    <li>Contine date personale ale altora fara consimtamant</li>
                </ul>

                <h2>7. Limitarea Raspunderii</h2>
                <p>
                    Prinde Momentul nu este responsabil pentru continutul incarcat de utilizatori. Ne rezervam dreptul de a sterge orice continut care incalca acesti termeni, fara notificare prealabila.
                </p>

                <h2>8. Disponibilitatea Serviciului</h2>
                <p>
                    Ne straduim sa mentinem serviciul disponibil, dar nu garantam disponibilitatea neintrerupta. Pot exista perioade de mentenanta sau intreruperi tehnice.
                </p>

                <h2>9. Modificari ale Termenilor</h2>
                <p>
                    Ne rezervam dreptul de a modifica acesti termeni in orice moment. Continuarea utilizarii serviciului dupa modificari constituie acceptarea noilor termeni.
                </p>

                <h2>10. Legea Aplicabila</h2>
                <p>
                    Acesti termeni sunt guvernati de legile din Romania. Orice disputa va fi solutionata de instantele competente din Romania.
                </p>

                <h2>11. Contact</h2>
                <p>Pentru intrebari despre acesti termeni, ne puteti contacta la:</p>
                <p><strong>Email:</strong> contact@prindemomentul.ro</p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-8 border-t border-slate-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm text-slate-400">&copy; {{ new Date().getFullYear() }} Prinde Momentul. Toate drepturile rezervate.</p>
            </div>
        </footer>
    </div>
</template>
