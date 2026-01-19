<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Politica GDPR" />

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
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">Politica de Confidentialitate</h1>
                <p class="mt-4 text-slate-500">Ultima actualizare: {{ new Date().toLocaleDateString('ro-RO') }}</p>
            </div>

            <div class="prose prose-slate max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-p:text-slate-600 prose-p:leading-relaxed prose-li:text-slate-600">
                <h2>1. Introducere</h2>
                <p>
                    Prinde Momentul respecta confidentialitatea datelor dumneavoastra personale si se angajeaza sa protejeze informatiile pe care ni le furnizati. Aceasta politica de confidentialitate explica modul in care colectam, folosim si protejam datele dumneavoastra personale in conformitate cu Regulamentul General privind Protectia Datelor (GDPR).
                </p>

                <h2>2. Date Colectate</h2>
                <p>Colectam urmatoarele tipuri de date:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Nume:</strong> Numele furnizat la incarcarea fotografiilor</li>
                    <li><strong>Fotografii si videoclipuri:</strong> Continutul media incarcat pe platforma</li>
                    <li><strong>Date tehnice:</strong> Adresa IP, tipul de browser, pentru functionarea serviciului</li>
                </ul>

                <h2>3. Scopul Prelucrarii</h2>
                <p>Datele dumneavoastra sunt prelucrate pentru:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Furnizarea serviciului de partajare a fotografiilor pentru evenimentul specificat</li>
                    <li>Permiterea organizatorilor evenimentului sa vizualizeze si descarce fotografiile</li>
                    <li>Imbunatatirea serviciilor noastre</li>
                </ul>

                <h2>4. Temeiul Legal</h2>
                <p>
                    Prelucrarea datelor se bazeaza pe consimtamantul dumneavoastra explicit, acordat prin bifarea casutei de acceptare inainte de incarcarea fotografiilor.
                </p>

                <h2>5. Stocarea Datelor</h2>
                <p>
                    Fotografiile si datele asociate sunt stocate pe servere securizate. Datele vor fi pastrate pe durata activa a evenimentului si pot fi sterse la cererea organizatorilor sau conform politicii noastre de retentie.
                </p>

                <h2>6. Drepturile Dumneavoastra</h2>
                <p>Conform GDPR, aveti urmatoarele drepturi:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Dreptul de acces:</strong> Puteti solicita o copie a datelor dumneavoastra</li>
                    <li><strong>Dreptul la rectificare:</strong> Puteti cere corectarea datelor incorecte</li>
                    <li><strong>Dreptul la stergere:</strong> Puteti cere stergerea datelor dumneavoastra</li>
                    <li><strong>Dreptul la restrictionarea prelucrarii:</strong> Puteti limita modul in care va folosim datele</li>
                    <li><strong>Dreptul la portabilitatea datelor:</strong> Puteti primi datele intr-un format structurat</li>
                    <li><strong>Dreptul de opozitie:</strong> Puteti obiecta la prelucrarea datelor</li>
                </ul>

                <h2>7. Securitatea Datelor</h2>
                <p>
                    Implementam masuri tehnice si organizatorice adecvate pentru a proteja datele dumneavoastra impotriva accesului neautorizat, pierderii sau distrugerii.
                </p>

                <h2>8. Partajarea Datelor</h2>
                <p>
                    Fotografiile incarcate sunt partajate doar cu organizatorii evenimentului pentru care au fost incarcate. Nu vindem si nu partajam datele dumneavoastra cu terti in scopuri de marketing.
                </p>

                <h2>9. Contact</h2>
                <p>
                    Pentru orice intrebari privind aceasta politica sau pentru a va exercita drepturile, ne puteti contacta la:
                </p>
                <p><strong>Email:</strong> contact@prindemomentul.ro</p>

                <h2>10. Modificari ale Politicii</h2>
                <p>
                    Ne rezervam dreptul de a modifica aceasta politica. Orice modificari vor fi publicate pe aceasta pagina cu data actualizarii.
                </p>
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
