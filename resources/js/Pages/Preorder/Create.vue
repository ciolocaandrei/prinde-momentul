<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

defineProps({
    eventTypes: Object,
});

const form = useForm({
    contact_name: '',
    contact_email: '',
    contact_phone: '',
    event_type: 'nunta',
    event_date: '',
    wants_print_package: false,
    wants_qr_card: false,
    qr_card_quantity: 50,
    qr_card_image: null,
    qr_card_theme: '',
    notes: '',
});

const useCustomImage = ref(true);
const imagePreview = ref(null);

const showQrOptions = computed(() => form.wants_qr_card);

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.qr_card_image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.qr_card_image = null;
    imagePreview.value = null;
};

watch(useCustomImage, (newVal) => {
    if (!newVal) {
        form.qr_card_image = null;
        imagePreview.value = null;
    } else {
        form.qr_card_theme = '';
    }
});

const submit = () => {
    form.post(route('preorder.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Precomanda - Prinde Momentul" />

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
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-slate-900 mb-4">
                    Rezerva serviciul nostru
                </h1>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Completeaza formularul de mai jos pentru a face o precomanda. Te vom contacta in cel mai scurt timp posibil.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Contact Information -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Date de Contact</h2>
                        <p class="mt-1 text-sm text-slate-500">Cum te putem contacta pentru a discuta detaliile.</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-slate-700">Nume complet *</label>
                                <input
                                    id="contact_name"
                                    type="text"
                                    v-model="form.contact_name"
                                    placeholder="ex: Ana Popescu"
                                    class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                    required
                                />
                                <p v-if="form.errors.contact_name" class="mt-2 text-sm text-red-600">{{ form.errors.contact_name }}</p>
                            </div>

                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-slate-700">Email *</label>
                                <input
                                    id="contact_email"
                                    type="email"
                                    v-model="form.contact_email"
                                    placeholder="email@exemplu.com"
                                    class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                    required
                                />
                                <p v-if="form.errors.contact_email" class="mt-2 text-sm text-red-600">{{ form.errors.contact_email }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-slate-700">Telefon</label>
                            <input
                                id="contact_phone"
                                type="tel"
                                v-model="form.contact_phone"
                                placeholder="ex: 0722 123 456"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                            />
                            <p v-if="form.errors.contact_phone" class="mt-2 text-sm text-red-600">{{ form.errors.contact_phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Detalii Eveniment</h2>
                        <p class="mt-1 text-sm text-slate-500">Spune-ne mai multe despre evenimentul tau.</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-slate-700">Tip Eveniment *</label>
                                <select
                                    id="event_type"
                                    v-model="form.event_type"
                                    class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                    required
                                >
                                    <option v-for="(label, value) in eventTypes" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.event_type" class="mt-2 text-sm text-red-600">{{ form.errors.event_type }}</p>
                            </div>

                            <div>
                                <label for="event_date" class="block text-sm font-medium text-slate-700">Data Evenimentului</label>
                                <input
                                    id="event_date"
                                    type="date"
                                    v-model="form.event_date"
                                    class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                />
                                <p v-if="form.errors.event_date" class="mt-2 text-sm text-red-600">{{ form.errors.event_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Options -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Optiuni Suplimentare</h2>
                        <p class="mt-1 text-sm text-slate-500">Alege optiunile care te intereseaza.</p>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        <!-- Print Package Option -->
                        <div class="flex items-start gap-x-4 p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <input
                                id="wants_print_package"
                                type="checkbox"
                                v-model="form.wants_print_package"
                                class="mt-1 h-5 w-5 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                            />
                            <div class="flex-1">
                                <label for="wants_print_package" class="block text-sm font-semibold text-slate-900 cursor-pointer">
                                    Pachet Printare 50 Fotografii
                                </label>
                                <p class="mt-1 text-sm text-slate-500">
                                    Primesti 50 de fotografii printate profesional, gata de oferit invitatilor tai.
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center rounded-full bg-violet-100 px-3 py-1 text-sm font-medium text-violet-700">
                                    Popular
                                </span>
                            </div>
                        </div>

                        <!-- QR Card Option -->
                        <div class="p-4 rounded-xl bg-slate-50">
                            <div class="flex items-start gap-x-4">
                                <input
                                    id="wants_qr_card"
                                    type="checkbox"
                                    v-model="form.wants_qr_card"
                                    class="mt-1 h-5 w-5 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                                />
                                <div class="flex-1">
                                    <label for="wants_qr_card" class="block text-sm font-semibold text-slate-900 cursor-pointer">
                                        Cartonas QR-Code Personalizat
                                    </label>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Cartonas fizic cu QR-code pentru invitatii tai sa poata incarca fotografii direct in galerie.
                                    </p>
                                </div>
                            </div>

                            <!-- QR Card Customization (shown only when QR card is selected) -->
                            <div v-if="showQrOptions" class="mt-6 ml-9 space-y-6 border-t border-slate-200 pt-6">
                                <!-- Quantity -->
                                <div>
                                    <label for="qr_card_quantity" class="block text-sm font-medium text-slate-700">Numar de cartonase *</label>
                                    <div class="mt-2 flex items-center gap-x-4">
                                        <input
                                            id="qr_card_quantity"
                                            type="number"
                                            v-model="form.qr_card_quantity"
                                            min="1"
                                            max="1000"
                                            class="block w-32 rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                            required
                                        />
                                        <span class="text-sm text-slate-500">bucati</span>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">Cate cartonase doresti sa printam? (minim 1, maxim 1000)</p>
                                    <p v-if="form.errors.qr_card_quantity" class="mt-2 text-sm text-red-600">{{ form.errors.qr_card_quantity }}</p>
                                </div>

                                <p class="text-sm font-medium text-slate-700">Personalizeaza cartonasul:</p>

                                <!-- Image vs Theme Toggle -->
                                <div class="flex gap-x-4">
                                    <button
                                        type="button"
                                        @click="useCustomImage = true"
                                        :class="[
                                            'flex-1 rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            useCustomImage
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Incarca o poza
                                    </button>
                                    <button
                                        type="button"
                                        @click="useCustomImage = false"
                                        :class="[
                                            'flex-1 rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            !useCustomImage
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Descrie o tema
                                    </button>
                                </div>

                                <!-- Image Upload -->
                                <div v-if="useCustomImage" class="space-y-4">
                                    <div
                                        v-if="!imagePreview"
                                        class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-violet-400 transition-colors cursor-pointer"
                                        @click="$refs.imageInput.click()"
                                    >
                                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-4 text-sm font-medium text-slate-900">Apasa pentru a incarca o imagine</p>
                                        <p class="mt-1 text-xs text-slate-500">PNG, JPG pana la 5MB</p>
                                        <input
                                            ref="imageInput"
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            @change="handleImageChange"
                                        />
                                    </div>

                                    <!-- Image Preview -->
                                    <div v-else class="relative">
                                        <img
                                            :src="imagePreview"
                                            alt="Preview"
                                            class="w-full h-48 object-cover rounded-xl"
                                        />
                                        <button
                                            type="button"
                                            @click="removeImage"
                                            class="absolute top-2 right-2 p-2 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p v-if="form.errors.qr_card_image" class="text-sm text-red-600">{{ form.errors.qr_card_image }}</p>
                                </div>

                                <!-- Theme Description -->
                                <div v-else>
                                    <label for="qr_card_theme" class="block text-sm font-medium text-slate-700">Descrie tema / paleta de culori dorita</label>
                                    <textarea
                                        id="qr_card_theme"
                                        v-model="form.qr_card_theme"
                                        rows="3"
                                        placeholder="ex: Tema florala cu nuante de roz pastel si auriu, cu elemente de eucalipt..."
                                        class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                    ></textarea>
                                    <p v-if="form.errors.qr_card_theme" class="mt-2 text-sm text-red-600">{{ form.errors.qr_card_theme }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Observatii</h2>
                        <p class="mt-1 text-sm text-slate-500">Alte detalii pe care vrei sa ni le transmiti.</p>
                    </div>
                    <div class="px-6 py-6">
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="4"
                            placeholder="Scrie aici orice alte detalii consideri relevante..."
                            class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                        ></textarea>
                        <p v-if="form.errors.notes" class="mt-2 text-sm text-red-600">{{ form.errors.notes }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-x-4 pt-4">
                    <Link
                        href="/"
                        class="rounded-xl px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                    >
                        Anuleaza
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 transition-all"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Trimite Precomanda
                    </button>
                </div>
            </form>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white/50 py-8 mt-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
                <p>&copy; {{ new Date().getFullYear() }} Prinde Momentul. Toate drepturile rezervate.</p>
            </div>
        </footer>
    </div>
</template>
