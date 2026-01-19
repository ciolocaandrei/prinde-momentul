<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Politica GDPR" />

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
                <h1 class="text-3xl font-bold text-slate-900 mb-8">Politica de Confidentialitate (GDPR)</h1>

                <div class="prose prose-slate max-w-none">
                    <p class="text-slate-600 mb-6">
                        Ultima actualizare: {{ new Date().toLocaleDateString('ro-RO') }}
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">1. Introducere</h2>
                    <p class="text-slate-600 mb-4">
                        Prinde Momentul respecta confidentialitatea datelor dumneavoastra personale si se angajeaza sa protejeze informatiile pe care ni le furnizati. Aceasta politica de confidentialitate explica modul in care colectam, folosim si protejam datele dumneavoastra personale in conformitate cu Regulamentul General privind Protectia Datelor (GDPR).
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">2. Date Colectate</h2>
                    <p class="text-slate-600 mb-4">Colectam urmatoarele tipuri de date:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li><strong>Nume:</strong> Numele furnizat la incarcarea fotografiilor</li>
                        <li><strong>Fotografii si videoclipuri:</strong> Continutul media incarcat pe platforma</li>
                        <li><strong>Date tehnice:</strong> Adresa IP, tipul de browser, pentru functionarea serviciului</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">3. Scopul Prelucrarii</h2>
                    <p class="text-slate-600 mb-4">Datele dumneavoastra sunt prelucrate pentru:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li>Furnizarea serviciului de partajare a fotografiilor pentru evenimentul specificat</li>
                        <li>Permiterea organizatorilor evenimentului sa vizualizeze si descarce fotografiile</li>
                        <li>Imbunatatirea serviciilor noastre</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">4. Temeiul Legal</h2>
                    <p class="text-slate-600 mb-4">
                        Prelucrarea datelor se bazeaza pe consimtamantul dumneavoastra explicit, acordat prin bifarea casutei de acceptare inainte de incarcarea fotografiilor.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">5. Stocarea Datelor</h2>
                    <p class="text-slate-600 mb-4">
                        Fotografiile si datele asociate sunt stocate pe servere securizate. Datele vor fi pastrate pe durata activa a evenimentului si pot fi sterse la cererea organizatorilor sau conform politicii noastre de retentie.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">6. Drepturile Dumneavoastra</h2>
                    <p class="text-slate-600 mb-4">Conform GDPR, aveti urmatoarele drepturi:</p>
                    <ul class="list-disc pl-6 text-slate-600 mb-4 space-y-2">
                        <li><strong>Dreptul de acces:</strong> Puteti solicita o copie a datelor dumneavoastra</li>
                        <li><strong>Dreptul la rectificare:</strong> Puteti cere corectarea datelor incorecte</li>
                        <li><strong>Dreptul la stergere:</strong> Puteti cere stergerea datelor dumneavoastra</li>
                        <li><strong>Dreptul la restrictionarea prelucrarii:</strong> Puteti limita modul in care va folosim datele</li>
                        <li><strong>Dreptul la portabilitatea datelor:</strong> Puteti primi datele intr-un format structurat</li>
                        <li><strong>Dreptul de opozitie:</strong> Puteti obiecta la prelucrarea datelor</li>
                    </ul>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">7. Securitatea Datelor</h2>
                    <p class="text-slate-600 mb-4">
                        Implementam masuri tehnice si organizatorice adecvate pentru a proteja datele dumneavoastra impotriva accesului neautorizat, pierderii sau distrugerii.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">8. Partajarea Datelor</h2>
                    <p class="text-slate-600 mb-4">
                        Fotografiile incarcate sunt partajate doar cu organizatorii evenimentului pentru care au fost incarcate. Nu vindem si nu partajam datele dumneavoastra cu terti in scopuri de marketing.
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">9. Contact</h2>
                    <p class="text-slate-600 mb-4">
                        Pentru orice intrebari privind aceasta politica sau pentru a va exercita drepturile, ne puteti contacta la:
                    </p>
                    <p class="text-slate-600 mb-4">
                        <strong>Email:</strong> contact@prindemomentul.ro
                    </p>

                    <h2 class="text-xl font-semibold text-slate-900 mt-8 mb-4">10. Modificari ale Politicii</h2>
                    <p class="text-slate-600 mb-4">
                        Ne rezervam dreptul de a modifica aceasta politica. Orice modificari vor fi publicate pe aceasta pagina cu data actualizarii.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
