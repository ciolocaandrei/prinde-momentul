<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CardCanvas from '@/Components/CardDesigner/CardCanvas.vue';
import { usePdfExport } from '@/composables/usePdfExport';

// Font imports for card designer
import '@fontsource-variable/playfair-display';
import '@fontsource-variable/cormorant';
import '@fontsource/great-vibes';
import '@fontsource/dancing-script';
import '@fontsource/montserrat/400.css';
import '@fontsource/montserrat/500.css';
import '@fontsource/montserrat/600.css';
import '@fontsource/montserrat/700.css';
import '@fontsource/lora/400.css';
import '@fontsource/lora/500.css';
import '@fontsource/lora/600.css';
import '@fontsource/lora/700.css';

const props = defineProps({
    preorder: Object,
    eventTypes: Object,
    statuses: Object,
});

const cardCanvasRef = ref(null);
const { isExporting, exportToPdf, exportToPng } = usePdfExport();

const downloadPdf = async () => {
    if (cardCanvasRef.value?.canvasRef && props.preorder.qr_card_design) {
        await exportToPdf(
            cardCanvasRef.value.canvasRef,
            props.preorder.qr_card_design,
            `qr-card-${props.preorder.contact_name.replace(/\s+/g, '-')}`
        );
    }
};

const downloadPng = async () => {
    if (cardCanvasRef.value?.canvasRef) {
        await exportToPng(
            cardCanvasRef.value.canvasRef,
            `qr-card-${props.preorder.contact_name.replace(/\s+/g, '-')}`
        );
    }
};

const statusColors = {
    pending: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    confirmed: 'bg-blue-50 text-blue-700 ring-blue-600/20',
    completed: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    cancelled: 'bg-red-50 text-red-700 ring-red-600/20',
};

const form = useForm({
    status: props.preorder.status,
});

const updateStatus = () => {
    form.patch(route('admin.preorders.update-status', props.preorder.id));
};
</script>

<template>
    <Head title="Detalii Precomanda" />

    <AdminLayout>
        <template #header>
            <div>
                <nav class="flex items-center gap-x-2 text-sm">
                    <Link :href="route('admin.preorders.index')" class="text-slate-500 hover:text-slate-700 transition-colors">Precomenzi</Link>
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-medium">{{ preorder.contact_name }}</span>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">Detalii Precomanda</h1>
            </div>
        </template>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Status Update -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Status Precomanda</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="flex items-center gap-x-4">
                        <select
                            v-model="form.status"
                            class="flex-1 rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                        >
                            <option v-for="(label, value) in statuses" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                        <button
                            @click="updateStatus"
                            :disabled="form.processing || form.status === preorder.status"
                            class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 transition-all"
                        >
                            <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Actualizeaza Status
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Date de Contact</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Nume</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ preorder.contact_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                <a :href="'mailto:' + preorder.contact_email" class="text-violet-600 hover:text-violet-800">
                                    {{ preorder.contact_email }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Telefon</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                <template v-if="preorder.contact_phone">
                                    <a :href="'tel:' + preorder.contact_phone" class="text-violet-600 hover:text-violet-800">
                                        {{ preorder.contact_phone }}
                                    </a>
                                </template>
                                <span v-else class="text-slate-400">-</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Data Precomanda</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                {{ new Date(preorder.created_at).toLocaleDateString('ro-RO', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Event Details -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Detalii Eveniment</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Tip Eveniment</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-sm font-medium text-slate-600">
                                    {{ preorder.event_type_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Data Eveniment</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                <template v-if="preorder.event_date">
                                    {{ new Date(preorder.event_date).toLocaleDateString('ro-RO', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) }}
                                </template>
                                <span v-else class="text-slate-400">Nespecificata</span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Package Options -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Optiuni Selectate</h2>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <!-- Print Package -->
                    <div class="flex items-center gap-x-4 p-4 rounded-xl" :class="preorder.wants_print_package ? 'bg-emerald-50' : 'bg-slate-50'">
                        <div :class="[preorder.wants_print_package ? 'bg-emerald-500' : 'bg-slate-300', 'flex h-10 w-10 items-center justify-center rounded-full']">
                            <svg v-if="preorder.wants_print_package" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" :class="preorder.wants_print_package ? 'text-emerald-900' : 'text-slate-600'">
                                Pachet Printare 50 Fotografii
                            </p>
                            <p class="text-sm" :class="preorder.wants_print_package ? 'text-emerald-700' : 'text-slate-500'">
                                {{ preorder.wants_print_package ? 'Da, doreste acest pachet' : 'Nu doreste acest pachet' }}
                            </p>
                        </div>
                    </div>

                    <!-- QR Card -->
                    <div class="flex items-start gap-x-4 p-4 rounded-xl" :class="preorder.wants_qr_card ? 'bg-emerald-50' : 'bg-slate-50'">
                        <div :class="[preorder.wants_qr_card ? 'bg-emerald-500' : 'bg-slate-300', 'flex h-10 w-10 items-center justify-center rounded-full flex-shrink-0']">
                            <svg v-if="preorder.wants_qr_card" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg v-else class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold" :class="preorder.wants_qr_card ? 'text-emerald-900' : 'text-slate-600'">
                                Cartonas QR-Code Personalizat
                            </p>
                            <p class="text-sm" :class="preorder.wants_qr_card ? 'text-emerald-700' : 'text-slate-500'">
                                {{ preorder.wants_qr_card ? 'Da, doreste acest pachet' : 'Nu doreste acest pachet' }}
                            </p>

                            <!-- QR Card Details -->
                            <div v-if="preorder.wants_qr_card" class="mt-4 space-y-4">
                                <!-- Quantity -->
                                <div class="flex items-center gap-x-2">
                                    <span class="text-sm font-medium text-emerald-800">Cantitate:</span>
                                    <span class="inline-flex items-center rounded-full bg-white/50 px-3 py-1 text-sm font-semibold text-emerald-900">
                                        {{ preorder.qr_card_quantity }} bucati
                                    </span>
                                </div>

                                <!-- Image -->
                                <div v-if="preorder.qr_card_image_url">
                                    <p class="text-sm font-medium text-emerald-800 mb-2">Imagine incarcata:</p>
                                    <img
                                        :src="preorder.qr_card_image_url"
                                        alt="QR Card Image"
                                        class="w-full max-w-sm rounded-xl shadow-md"
                                    />
                                </div>

                                <!-- Sample Image -->
                                <div v-if="preorder.qr_card_sample">
                                    <p class="text-sm font-medium text-emerald-800 mb-2">Design din exemple:</p>

                                    <!-- Sample Details -->
                                    <div v-if="preorder.qr_card_sample_names || preorder.qr_card_sample_date" class="bg-white/50 rounded-lg p-3 mb-3 space-y-1">
                                        <p v-if="preorder.qr_card_sample_names" class="text-sm">
                                            <span class="font-medium text-emerald-800">Nume:</span>
                                            <span class="text-emerald-700 ml-2">{{ preorder.qr_card_sample_names }}</span>
                                        </p>
                                        <p v-if="preorder.qr_card_sample_date" class="text-sm">
                                            <span class="font-medium text-emerald-800">Data:</span>
                                            <span class="text-emerald-700 ml-2">{{ preorder.qr_card_sample_date }}</span>
                                        </p>
                                    </div>

                                    <img
                                        :src="preorder.qr_card_sample"
                                        alt="QR Card Sample"
                                        class="w-full max-w-sm rounded-xl shadow-md"
                                    />
                                    <a
                                        :href="preorder.qr_card_sample"
                                        download
                                        class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-violet-600 text-white text-sm font-medium rounded-xl hover:bg-violet-700 transition-colors"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Descarca imaginea
                                    </a>
                                </div>

                                <!-- Theme -->
                                <div v-if="preorder.qr_card_theme">
                                    <p class="text-sm font-medium text-emerald-800 mb-2">Tema descrisa:</p>
                                    <p class="text-sm text-emerald-700 bg-white/50 rounded-lg p-3">
                                        {{ preorder.qr_card_theme }}
                                    </p>
                                </div>

                                <!-- Custom Card Design -->
                                <div v-if="preorder.qr_card_design">
                                    <p class="text-sm font-medium text-emerald-800 mb-3">Design personalizat:</p>

                                    <!-- Card Preview -->
                                    <div class="bg-white/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-center">
                                            <CardCanvas
                                                ref="cardCanvasRef"
                                                :design="preorder.qr_card_design"
                                                :qr-url="`https://nunta360.ro/upload/${preorder.id}`"
                                                :scale="2.5"
                                                :interactive="false"
                                            />
                                        </div>
                                        <p class="text-center text-xs text-emerald-600 mt-3">
                                            {{ preorder.qr_card_design.dimensions.width }} × {{ preorder.qr_card_design.dimensions.height }} {{ preorder.qr_card_design.dimensions.unit }}
                                        </p>
                                    </div>

                                    <!-- Download Buttons -->
                                    <div class="flex gap-3">
                                        <button
                                            type="button"
                                            @click="downloadPdf"
                                            :disabled="isExporting"
                                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-violet-600 text-white text-sm font-medium rounded-xl hover:bg-violet-700 disabled:opacity-50 transition-colors"
                                        >
                                            <svg v-if="isExporting" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Descarca PDF
                                        </button>
                                        <button
                                            type="button"
                                            @click="downloadPng"
                                            :disabled="isExporting"
                                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white text-emerald-700 text-sm font-medium rounded-xl border border-emerald-200 hover:bg-emerald-50 disabled:opacity-50 transition-colors"
                                        >
                                            <svg v-if="isExporting" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg v-else class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Descarca PNG
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div v-if="preorder.notes" class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Observatii Suplimentare</h2>
                </div>
                <div class="px-6 py-6">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ preorder.notes }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4">
                <Link
                    :href="route('admin.preorders.index')"
                    class="inline-flex items-center gap-x-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Inapoi la Lista
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>
