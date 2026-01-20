<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { useCardDesign } from '@/composables/useCardDesign';
import CardCanvas from '@/Components/CardDesigner/CardCanvas.vue';
import DesignToolbar from '@/Components/CardDesigner/DesignToolbar.vue';
import ExportModal from '@/Components/CardDesigner/ExportModal.vue';
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
    eventTypes: Object,
    sampleImages: {
        type: Array,
        default: () => [],
    },
    designerTemplates: {
        type: Array,
        default: () => [
            {
                id: 'elegant_gold',
                name: 'Elegant Gold',
                style: 'Clasic',
                background: { type: 'gradient', colors: ['#FFFEF7', '#F5F0E1'], direction: '135deg' },
                primaryFont: 'Playfair Display',
                secondaryFont: 'Montserrat',
                textColor: '#2C2C2C',
                accentColor: '#C9A959',
            },
            {
                id: 'modern_minimal',
                name: 'Modern Minimal',
                style: 'Minimalist',
                background: { type: 'solid', color: '#FFFFFF' },
                primaryFont: 'Montserrat',
                secondaryFont: 'Montserrat',
                textColor: '#1A1A1A',
                accentColor: '#666666',
            },
            {
                id: 'romantic_blush',
                name: 'Romantic Blush',
                style: 'Romantic',
                background: { type: 'gradient', colors: ['#FDF2F8', '#EDE9FE'], direction: '135deg' },
                primaryFont: 'Great Vibes',
                secondaryFont: 'Lora',
                textColor: '#4A3728',
                accentColor: '#D4A5A5',
            },
            {
                id: 'garden_party',
                name: 'Garden Party',
                style: 'Natural',
                background: { type: 'solid', color: '#F0F4F0' },
                primaryFont: 'Cormorant',
                secondaryFont: 'Montserrat',
                textColor: '#2D4A3E',
                accentColor: '#7C9A8D',
            },
            {
                id: 'classic_navy',
                name: 'Classic Navy',
                style: 'Sofisticat',
                background: { type: 'solid', color: '#1E3A5F' },
                primaryFont: 'Playfair Display',
                secondaryFont: 'Montserrat',
                textColor: '#FFFFFF',
                accentColor: '#C9A959',
            },
            {
                id: 'rustic_charm',
                name: 'Rustic Charm',
                style: 'Rustic',
                background: { type: 'solid', color: '#FAF6F1' },
                primaryFont: 'Dancing Script',
                secondaryFont: 'Lora',
                textColor: '#5C4033',
                accentColor: '#8B7355',
            },
        ],
    },
    designerFonts: {
        type: Array,
        default: () => [
            { id: 'Playfair Display', name: 'Playfair Display', category: 'Serif' },
            { id: 'Montserrat', name: 'Montserrat', category: 'Sans-serif' },
            { id: 'Great Vibes', name: 'Great Vibes', category: 'Script' },
            { id: 'Dancing Script', name: 'Dancing Script', category: 'Script' },
            { id: 'Cormorant', name: 'Cormorant', category: 'Serif' },
            { id: 'Lora', name: 'Lora', category: 'Serif' },
        ],
    },
    designerSizes: {
        type: Array,
        default: () => [
            { id: 'business_card', name: 'Business Card', width: 85, height: 55, unit: 'mm' },
            { id: 'a7', name: 'A7', width: 74, height: 105, unit: 'mm' },
            { id: 'a6', name: 'A6', width: 105, height: 148, unit: 'mm' },
        ],
    },
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
    qr_card_sample: null,
    qr_card_theme: '',
    qr_card_design: null,
    notes: '',
});

const useCustomImage = ref(false);
const useDesigner = ref(false);
// Default to predesign when no samples available
const usePredesign = ref(props.sampleImages.length === 0);
const useSamples = ref(props.sampleImages.length > 0);
const imagePreview = ref(null);
const showDesignerModal = ref(false);
const hasCustomDesign = ref(false);
const designPreviewRef = ref(null);

// Predesign state
const selectedPredesignId = ref(null);
const predesignNames = ref('');
const predesignDate = ref('');
const predesignPreviewRef = ref(null);
const showFullscreenPreview = ref(false);

// Samples state
const selectedSampleId = ref(null);
const showFullscreenSample = ref(false);
const fullscreenSamplePath = ref(null);

const selectedSample = computed(() => {
    if (!selectedSampleId.value) return null;
    return props.sampleImages.find(s => s.id === selectedSampleId.value);
});

const showQrOptions = computed(() => form.wants_qr_card);

const selectedPredesign = computed(() => {
    if (!selectedPredesignId.value) return null;
    return props.designerTemplates.find(t => t.id === selectedPredesignId.value);
});

// Card Designer Setup
const {
    design: cardDesign,
    selectedElementId,
    setDimensions,
    setBackgroundType,
    setBackgroundColor,
    setGradientColors,
    setGradientDirection,
    selectElement,
    deselectElement,
    updateTextContent,
    updateTextStyle,
    updateTextPosition,
    updateQrPosition,
    updateQrSize,
    updateQrColors,
    applyTemplate,
    exportDesign,
    importDesign,
} = useCardDesign();

const { isExporting, exportToPdf, exportToPng } = usePdfExport();

const selectedTemplateId = ref(null);
const showExportModal = ref(false);
const cardCanvasRef = ref(null);

const qrUrl = computed(() => 'https://nunta360.ro/upload/DEMO');

const openDesigner = () => {
    showDesignerModal.value = true;
};

const closeDesigner = () => {
    showDesignerModal.value = false;
};

const handleSelectElement = (id) => {
    if (id) {
        selectElement(id);
    } else {
        deselectElement();
    }
};

const handleUpdateDimensions = (preset, width, height, unit) => {
    setDimensions(preset, width, height, unit);
};

const handleUpdateBackground = (background) => {
    setBackgroundType(background.type);
    if (background.type === 'solid') {
        setBackgroundColor(background.color);
    } else {
        setGradientColors(background.gradient.colors);
        setGradientDirection(background.gradient.direction);
    }
};

const handleApplyTemplate = (template) => {
    selectedTemplateId.value = template.id;
    applyTemplate(template);
};

const saveDesign = () => {
    const designData = exportDesign();
    form.qr_card_design = designData;
    hasCustomDesign.value = true;
    showDesignerModal.value = false;
};

const handleExport = () => {
    showExportModal.value = true;
};

const handleExportPdf = async () => {
    if (cardCanvasRef.value?.canvasRef) {
        await exportToPdf(cardCanvasRef.value.canvasRef, cardDesign, 'qr-card');
        showExportModal.value = false;
    }
};

const handleExportPng = async () => {
    if (cardCanvasRef.value?.canvasRef) {
        await exportToPng(cardCanvasRef.value.canvasRef, 'qr-card');
        showExportModal.value = false;
    }
};

// Predesign functions
const selectPredesign = (template) => {
    selectedPredesignId.value = template.id;
    applyTemplate(template);
    // Update names if already entered
    if (predesignNames.value) {
        updateTextContent('names', predesignNames.value);
    }
    if (predesignDate.value) {
        updateTextContent('date', predesignDate.value);
    }
};

const updatePredesignNames = () => {
    if (selectedPredesignId.value && predesignNames.value) {
        updateTextContent('names', predesignNames.value);
    }
};

const updatePredesignDate = () => {
    if (selectedPredesignId.value && predesignDate.value) {
        updateTextContent('date', predesignDate.value);
    }
};

const savePredesign = () => {
    if (selectedPredesignId.value) {
        const designData = exportDesign();
        form.qr_card_design = designData;
        hasCustomDesign.value = true;
    }
};

// Auto-save predesign when names or template changes
watch([selectedPredesignId, predesignNames, predesignDate], () => {
    if (selectedPredesignId.value) {
        savePredesign();
    }
}, { deep: true });

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

watch(useDesigner, (newVal) => {
    if (newVal) {
        // Using designer - clear image and theme
        form.qr_card_image = null;
        form.qr_card_theme = '';
        imagePreview.value = null;
        useCustomImage.value = false;
        usePredesign.value = false;
    } else if (!usePredesign.value) {
        // Not using designer or predesign - clear design
        form.qr_card_design = null;
        hasCustomDesign.value = false;
    }
});

watch(usePredesign, (newVal) => {
    if (newVal) {
        // Using predesign - clear image, theme, sample
        form.qr_card_image = null;
        form.qr_card_theme = '';
        form.qr_card_sample = null;
        imagePreview.value = null;
        useCustomImage.value = false;
        useDesigner.value = false;
        useSamples.value = false;
        selectedSampleId.value = null;
    } else if (!useDesigner.value && !useSamples.value) {
        // Not using predesign or designer or samples - clear design
        form.qr_card_design = null;
        hasCustomDesign.value = false;
        selectedPredesignId.value = null;
    }
});

watch(useSamples, (newVal) => {
    if (newVal) {
        // Using samples - clear image, theme, design
        form.qr_card_image = null;
        form.qr_card_theme = '';
        form.qr_card_design = null;
        imagePreview.value = null;
        useCustomImage.value = false;
        useDesigner.value = false;
        usePredesign.value = false;
        hasCustomDesign.value = false;
        selectedPredesignId.value = null;
    } else {
        // Not using samples - clear sample
        form.qr_card_sample = null;
        selectedSampleId.value = null;
    }
});

const selectSample = (sample) => {
    selectedSampleId.value = sample.id;
    form.qr_card_sample = sample.path;
};

const submit = () => {
    // Stringify qr_card_design for FormData compatibility
    if (form.qr_card_design) {
        form.transform((data) => ({
            ...data,
            qr_card_design: JSON.stringify(data.qr_card_design),
        })).post(route('preorder.store'), {
            forceFormData: true,
        });
    } else {
        form.post(route('preorder.store'), {
            forceFormData: true,
        });
    }
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

                                <!-- Samples vs Predesign vs Designer vs Image vs Theme Toggle -->
                                <div class="grid grid-cols-2 gap-2">
                                    <button
                                        v-if="sampleImages.length > 0"
                                        type="button"
                                        @click="useSamples = true"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            useSamples
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Din exemple
                                    </button>
                                    <button
                                        type="button"
                                        @click="usePredesign = true"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            usePredesign
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Alege design
                                    </button>
                                    <button
                                        type="button"
                                        @click="useDesigner = true"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            useDesigner
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Designer avansat
                                    </button>
                                    <button
                                        type="button"
                                        @click="useSamples = false; usePredesign = false; useDesigner = false; useCustomImage = true"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all',
                                            !useSamples && !useDesigner && !usePredesign && useCustomImage
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Incarca poza
                                    </button>
                                    <button
                                        type="button"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all col-span-2',
                                            !useSamples && !useDesigner && !usePredesign && !useCustomImage
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                        @click="useSamples = false; usePredesign = false; useDesigner = false; useCustomImage = false"
                                    >
                                        Descrie tema
                                    </button>
                                </div>

                                <!-- Samples Option -->
                                <div v-if="useSamples && sampleImages.length > 0" class="space-y-4">
                                    <p class="text-sm text-slate-600">Alege unul din modelele noastre:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        <div
                                            v-for="sample in sampleImages"
                                            :key="sample.id"
                                            class="rounded-xl overflow-hidden transition-all hover:scale-[1.02]"
                                            :class="[
                                                selectedSampleId === sample.id
                                                    ? 'ring-2 ring-violet-500 ring-offset-2 shadow-lg'
                                                    : 'ring-1 ring-slate-200 hover:ring-slate-300 shadow-sm'
                                            ]"
                                        >
                                            <div class="relative group cursor-pointer" @click="fullscreenSamplePath = sample.path; showFullscreenSample = true">
                                                <img
                                                    :src="sample.path"
                                                    :alt="sample.name"
                                                    class="w-full aspect-[85/55] object-cover"
                                                />
                                                <!-- Fullscreen overlay icon -->
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                @click="selectSample(sample)"
                                                class="w-full p-2 bg-white text-center hover:bg-slate-50 transition-colors"
                                            >
                                                <div class="text-xs font-medium text-slate-800 truncate">
                                                    {{ selectedSampleId === sample.id ? '✓ ' : '' }}{{ sample.name }}
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Selected Sample Preview -->
                                    <div v-if="selectedSample" class="p-4 bg-emerald-50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="text-sm font-medium text-emerald-800">
                                                Ai selectat: {{ selectedSample.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Predesign Option -->
                                <div v-else-if="usePredesign" class="space-y-4">
                                    <!-- Name and Date Inputs -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Numele (ex: Ana & Mihai)</label>
                                            <input
                                                type="text"
                                                v-model="predesignNames"
                                                @input="updatePredesignNames"
                                                placeholder="Ana & Mihai"
                                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Data evenimentului</label>
                                            <input
                                                type="text"
                                                v-model="predesignDate"
                                                @input="updatePredesignDate"
                                                placeholder="12 August 2026"
                                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                            />
                                        </div>
                                    </div>

                                    <!-- Template Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Alege un design:</label>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            <button
                                                v-for="template in designerTemplates"
                                                :key="template.id"
                                                type="button"
                                                @click="selectPredesign(template)"
                                                class="rounded-xl overflow-hidden transition-all hover:scale-[1.02]"
                                                :class="[
                                                    selectedPredesignId === template.id
                                                        ? 'ring-2 ring-violet-500 ring-offset-2 shadow-lg'
                                                        : 'ring-1 ring-slate-200 hover:ring-slate-300 shadow-sm'
                                                ]"
                                            >
                                                <div
                                                    class="aspect-[85/55] p-2 flex items-center"
                                                    :style="template.background.type === 'gradient'
                                                        ? { background: `linear-gradient(${template.background.direction}, ${template.background.colors[0]}, ${template.background.colors[1]})` }
                                                        : { backgroundColor: template.background.color }"
                                                >
                                                    <div class="flex-1 flex flex-col items-center justify-center gap-0.5">
                                                        <div
                                                            class="text-[9px] font-semibold truncate"
                                                            :style="{ color: template.textColor, fontFamily: template.primaryFont }"
                                                        >
                                                            {{ predesignNames || 'Ana & Mihai' }}
                                                        </div>
                                                        <div
                                                            class="text-[6px] truncate"
                                                            :style="{ color: template.accentColor, fontFamily: template.secondaryFont }"
                                                        >
                                                            {{ predesignDate || '12 August 2026' }}
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col items-center gap-0.5">
                                                        <div
                                                            class="w-6 h-6 rounded flex items-center justify-center text-[5px]"
                                                            :style="{ backgroundColor: template.background.type === 'solid' && template.textColor === '#FFFFFF' ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.1)', color: template.textColor }"
                                                        >
                                                            QR
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-2 bg-white text-center">
                                                    <div class="text-xs font-medium text-slate-800">{{ template.name }}</div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div v-if="selectedPredesignId" class="p-4 bg-slate-50 rounded-xl">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-sm font-medium text-slate-700">Preview:</p>
                                            <button
                                                type="button"
                                                @click="showFullscreenPreview = true"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                </svg>
                                                Ecran complet
                                            </button>
                                        </div>
                                        <div class="flex justify-center">
                                            <CardCanvas
                                                ref="predesignPreviewRef"
                                                :design="cardDesign"
                                                :qr-url="qrUrl"
                                                :scale="2"
                                                :interactive="false"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Designer Option -->
                                <div v-else-if="useDesigner" class="space-y-4">
                                    <!-- Design Preview (if has design) -->
                                    <div v-if="hasCustomDesign" class="relative p-4 bg-slate-50 rounded-xl">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <CardCanvas
                                                    ref="designPreviewRef"
                                                    :design="cardDesign"
                                                    :qr-url="qrUrl"
                                                    :scale="1"
                                                    :interactive="false"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-slate-900">Design personalizat</p>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    {{ cardDesign.dimensions.width }} × {{ cardDesign.dimensions.height }} {{ cardDesign.dimensions.unit }}
                                                </p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="openDesigner"
                                                class="px-3 py-1.5 text-sm text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition-colors"
                                            >
                                                Editeaza
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Open Designer Button -->
                                    <button
                                        v-else
                                        type="button"
                                        @click="openDesigner"
                                        class="w-full p-6 border-2 border-dashed border-violet-300 rounded-xl text-center hover:border-violet-400 hover:bg-violet-50/50 transition-all group"
                                    >
                                        <div class="w-12 h-12 mx-auto rounded-full bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold text-violet-600">Deschide Designer-ul</p>
                                        <p class="mt-1 text-xs text-slate-500">Creeaza un design unic pentru cartonasul tau QR</p>
                                    </button>
                                </div>

                                <!-- Image Upload -->
                                <div v-else-if="!usePredesign && !useDesigner && useCustomImage" class="space-y-4">
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

        <!-- Fullscreen Card Designer Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showDesignerModal"
                    class="fixed inset-0 z-[100] bg-gray-100"
                >
                    <!-- Designer Header -->
                    <header class="bg-white border-b border-gray-200 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <button
                                    type="button"
                                    @click="closeDesigner"
                                    class="text-gray-500 hover:text-gray-700 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <h1 class="text-lg font-semibold text-gray-900">
                                    Designer Cartonas QR
                                </h1>
                            </div>

                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors"
                                    @click="handleExport"
                                >
                                    Export PDF
                                </button>
                                <button
                                    type="button"
                                    class="px-4 py-2 text-sm bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition-colors"
                                    @click="saveDesign"
                                >
                                    Salveaza Design
                                </button>
                            </div>
                        </div>
                    </header>

                    <!-- Designer Content -->
                    <div class="flex h-[calc(100vh-65px)]">
                        <!-- Sidebar -->
                        <aside class="w-80 flex-shrink-0 border-r border-gray-200 bg-white overflow-hidden">
                            <DesignToolbar
                                :design="cardDesign"
                                :templates="designerTemplates"
                                :fonts="designerFonts"
                                :sizes="designerSizes"
                                :selected-element-id="selectedElementId"
                                :selected-template="selectedTemplateId"
                                @update-dimensions="handleUpdateDimensions"
                                @update-background="handleUpdateBackground"
                                @update-text-content="updateTextContent"
                                @update-text-style="updateTextStyle"
                                @update-text-position="updateTextPosition"
                                @update-qr-position="updateQrPosition"
                                @update-qr-size="updateQrSize"
                                @update-qr-colors="updateQrColors"
                                @apply-template="handleApplyTemplate"
                                @select-element="handleSelectElement"
                                @save="saveDesign"
                                @export="handleExport"
                            />
                        </aside>

                        <!-- Canvas Area -->
                        <main class="flex-1 flex items-center justify-center p-8 bg-gray-100">
                            <div class="relative">
                                <div class="bg-white p-8 rounded-2xl shadow-xl">
                                    <CardCanvas
                                        ref="cardCanvasRef"
                                        :design="cardDesign"
                                        :qr-url="qrUrl"
                                        :scale="3"
                                        :selected-element-id="selectedElementId"
                                        :interactive="true"
                                        @select-element="handleSelectElement"
                                    />
                                </div>
                                <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">
                                    {{ cardDesign.dimensions.width }} x {{ cardDesign.dimensions.height }} {{ cardDesign.dimensions.unit }}
                                </div>
                            </div>
                        </main>
                    </div>

                    <!-- Export Modal -->
                    <ExportModal
                        :show="showExportModal"
                        :is-exporting="isExporting"
                        :design="cardDesign"
                        @close="showExportModal = false"
                        @export-pdf="handleExportPdf"
                        @export-png="handleExportPng"
                    />
                </div>
            </Transition>
        </Teleport>

        <!-- Fullscreen Preview Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showFullscreenPreview"
                    class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4"
                    @click.self="showFullscreenPreview = false"
                >
                    <!-- Close Button -->
                    <button
                        type="button"
                        @click="showFullscreenPreview = false"
                        class="absolute top-4 right-4 p-2 text-white/70 hover:text-white transition-colors"
                    >
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Card Preview -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-2xl max-w-full overflow-auto">
                        <CardCanvas
                            :design="cardDesign"
                            :qr-url="qrUrl"
                            :scale="4"
                            :interactive="false"
                        />
                    </div>

                    <!-- Info -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-center">
                        <p class="text-white/70 text-sm">
                            {{ cardDesign.dimensions.width }} x {{ cardDesign.dimensions.height }} {{ cardDesign.dimensions.unit }}
                        </p>
                        <p class="text-white/50 text-xs mt-1">Click afara sau apasa X pentru a inchide</p>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Fullscreen Sample Image Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showFullscreenSample"
                    class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4"
                    @click.self="showFullscreenSample = false"
                >
                    <!-- Close Button -->
                    <button
                        type="button"
                        @click="showFullscreenSample = false"
                        class="absolute top-4 right-4 p-2 text-white/70 hover:text-white transition-colors z-10"
                    >
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Sample Image -->
                    <img
                        :src="fullscreenSamplePath"
                        alt="Sample preview"
                        class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                    />

                    <!-- Info -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-center">
                        <p class="text-white/50 text-xs">Click afara sau apasa X pentru a inchide</p>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
