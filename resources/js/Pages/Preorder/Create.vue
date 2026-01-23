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
        type: Object,
        default: () => ({}),
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
    qr_card_sample_names: '',
    qr_card_sample_date: '',
    qr_card_theme: '',
    qr_card_design: null,
    notes: '',
    terms_accepted: false,
});

const useCustomImage = ref(false);
const useDesigner = ref(false);
// Check if there are any samples
const hasSamples = computed(() => Object.keys(props.sampleImages).length > 0);
// Default to predesign when no samples available
const usePredesign = ref(Object.keys(props.sampleImages).length === 0);
const useSamples = ref(Object.keys(props.sampleImages).length > 0);
const imagePreview = ref(null);
const showDesignerModal = ref(false);
const showMobileToolbar = ref(false);
const mobileCanvasScale = ref(1.5);
const hasCustomDesign = ref(false);
const designPreviewRef = ref(null);

// Predesign state
const selectedPredesignId = ref(null);
const selectedPredesignSize = ref('business_card');
const predesignNames = ref('');
const predesignDate = ref('');
const predesignPreviewRef = ref(null);
const showFullscreenPreview = ref(false);

// Samples state
const selectedSampleId = ref(null);
const showFullscreenSample = ref(false);
const fullscreenSamplePath = ref(null);
const sampleNames = ref('');
const sampleDate = ref('');

// Map event types to sample categories
const eventTypeToCategory = {
    'nunta': 'nunta',
    'botez': 'botez',
    'majorat': 'majorat',
};

const currentSampleCategory = computed(() => {
    return eventTypeToCategory[form.event_type] || 'nunta';
});

const samplesForEventType = computed(() => {
    const category = currentSampleCategory.value;
    if (!props.sampleImages[category]) {
        return [];
    }
    return props.sampleImages[category].samples;
});

const selectedSample = computed(() => {
    if (!selectedSampleId.value) return null;
    for (const category of Object.values(props.sampleImages)) {
        const found = category.samples.find(s => s.id === selectedSampleId.value);
        if (found) return found;
    }
    return null;
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
    showMobileToolbar.value = false;
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

const selectPredesignSize = (size) => {
    selectedPredesignSize.value = size.id;
    setDimensions(size.id, size.width, size.height, size.unit);
    savePredesign();
};

const savePredesign = () => {
    if (selectedPredesignId.value) {
        const designData = exportDesign();
        form.qr_card_design = designData;
        hasCustomDesign.value = true;
    }
};

// Auto-save predesign when names, template, or size changes
watch([selectedPredesignId, predesignNames, predesignDate, selectedPredesignSize], () => {
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
        // Using designer - clear other options
        form.qr_card_image = null;
        form.qr_card_theme = '';
        form.qr_card_sample = null;
        imagePreview.value = null;
        useCustomImage.value = false;
        usePredesign.value = false;
        useSamples.value = false;
        selectedSampleId.value = null;
    } else if (!usePredesign.value && !useSamples.value) {
        // Not using designer or predesign or samples - clear design
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

// Check if QR card design is valid
const hasValidCardDesign = computed(() => {
    if (!form.wants_qr_card) return true;
    return form.qr_card_sample || form.qr_card_design;
});

const cardDesignError = ref('');

// Price calculation
const PRICE_BASE = 299.99;
const PRICE_PER_QR_CARD = 1.99;
const PRICE_PER_PHOTO = 1.49;
const PRINT_PACKAGE_PHOTOS = 50;

const priceBreakdown = computed(() => {
    const items = [];
    let total = PRICE_BASE;

    items.push({
        label: 'Pachet standard eveniment',
        quantity: 1,
        unitPrice: PRICE_BASE,
        subtotal: PRICE_BASE,
    });

    if (form.wants_qr_card && form.qr_card_quantity > 0) {
        const qrSubtotal = form.qr_card_quantity * PRICE_PER_QR_CARD;
        items.push({
            label: 'Cartonașe QR imprimate',
            quantity: form.qr_card_quantity,
            unitPrice: PRICE_PER_QR_CARD,
            subtotal: qrSubtotal,
        });
        total += qrSubtotal;
    }

    if (form.wants_print_package) {
        const printSubtotal = PRINT_PACKAGE_PHOTOS * PRICE_PER_PHOTO;
        items.push({
            label: 'Pachet printare fotografii',
            quantity: PRINT_PACKAGE_PHOTOS,
            unitPrice: PRICE_PER_PHOTO,
            subtotal: printSubtotal,
        });
        total += printSubtotal;
    }

    return { items, total };
});

const submit = () => {
    // Validate card design if QR card is selected
    if (form.wants_qr_card) {
        // Check if sample or design is selected
        if (!form.qr_card_sample && !form.qr_card_design) {
            cardDesignError.value = 'Te rugăm să alegi un model din exemple sau să creezi un design personalizat.';
            return;
        }

        // If using samples, require name and date
        if (useSamples.value && form.qr_card_sample) {
            if (!form.qr_card_sample_names || !form.qr_card_sample_date) {
                cardDesignError.value = 'Te rugăm să completezi numele și data evenimentului.';
                return;
            }
        }

        // If using predesign, require name and date
        if (usePredesign.value && form.qr_card_design) {
            if (!predesignNames.value || !predesignDate.value) {
                cardDesignError.value = 'Te rugăm să completezi numele și data evenimentului.';
                return;
            }
        }
    }
    cardDesignError.value = '';

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
    <Head>
        <title>Precomanda Servicii Foto Eveniment - Prinde Momentul</title>
        <meta name="description" content="Rezerva acum serviciul de colectare fotografii pentru nunta, botez sau majorat. Pret de la 299.99 lei. QR code personalizat si galerie privata." />
        <meta name="keywords" content="precomanda fotografii nunta, rezervare serviciu foto, QR code nunta pret, galerie foto eveniment" />
        <meta property="og:title" content="Precomanda Servicii Foto - Prinde Momentul" />
        <meta property="og:description" content="Rezerva serviciul de colectare fotografii pentru evenimentul tau. Pret de la 299.99 lei." />
        <link rel="canonical" href="https://prindemomentul.ro/precomanda" />
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
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <!-- Hero Section -->
            <div class="text-center mb-10 sm:mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Folosit la peste 150+ evenimente
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 mb-4 sm:mb-6">
                    Toate fotografiile invitaților,<br class="hidden sm:block" />
                    <span class="bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">într-un singur loc</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-6">
                    Oferă invitaților un cod QR elegant. Ei încarcă pozele de pe telefon, tu primești toate amintirile într-o galerie privată.
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Fără aplicație de instalat
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Galerie privată securizată
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Cartonașe QR personalizate
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="mb-10 sm:mb-16">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 text-center mb-8">Cum funcționează?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-lg mb-3">1</div>
                        <h3 class="font-semibold text-slate-900 mb-1">Primești cartonașele QR</h3>
                        <p class="text-sm text-slate-500">Cartonașe elegante personalizate cu numele și data evenimentului tău</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-lg mb-3">2</div>
                        <h3 class="font-semibold text-slate-900 mb-1">Invitații scanează și încarcă</h3>
                        <p class="text-sm text-slate-500">Fără aplicație - funcționează direct din browser pe orice telefon</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-lg mb-3">3</div>
                        <h3 class="font-semibold text-slate-900 mb-1">Tu ai toate pozele</h3>
                        <p class="text-sm text-slate-500">Accesezi galeria privată și descarci toate amintirile</p>
                    </div>
                </div>
            </div>

            <!-- Social Proof -->
            <div class="mb-10 sm:mb-16 bg-white rounded-2xl p-6 sm:p-8 shadow-sm ring-1 ring-slate-900/5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-semibold">AM</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1 mb-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                            <p class="text-sm text-slate-600 italic">"Am strâns peste 400 de poze de la invitați! Cea mai bună idee pentru nuntă."</p>
                            <p class="text-xs text-slate-400 mt-1">Ana & Mihai - Nuntă București</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-400 flex items-center justify-center text-white font-semibold">RC</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1 mb-1">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                            <p class="text-sm text-slate-600 italic">"Super simplu pentru invitați. Bunicii au reușit să trimită poze fără ajutor!"</p>
                            <p class="text-xs text-slate-400 mt-1">Raluca & Cristi - Nuntă Cluj</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section Title -->
            <div class="text-center mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">Rezervă acum pentru evenimentul tău</h2>
                <p class="text-slate-500">Completează formularul și te contactăm în 24h pentru confirmare</p>
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
                            <div class="flex-shrink-0 text-right">
                                <span class="block text-lg font-bold text-violet-600">74.50 lei</span>
                                <span class="text-xs text-slate-500">50 x 1.49 lei</span>
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
                                <div class="flex-shrink-0 text-right">
                                    <span class="block text-lg font-bold text-violet-600">1.99 lei</span>
                                    <span class="text-xs text-slate-500">per bucata</span>
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
                                        <span class="text-sm font-medium text-violet-600">= {{ (form.qr_card_quantity * 1.99).toFixed(2) }} lei</span>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">Cate cartonase doresti sa printam? (minim 1, maxim 1000)</p>
                                    <p v-if="form.errors.qr_card_quantity" class="mt-2 text-sm text-red-600">{{ form.errors.qr_card_quantity }}</p>
                                </div>

                                <p class="text-sm font-medium text-slate-700">Personalizeaza cartonasul:</p>

                                <!-- Samples vs Predesign vs Designer Toggle -->
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-if="hasSamples"
                                        type="button"
                                        @click="useSamples = true"
                                        :class="[
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all flex-1',
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
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all flex-1',
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
                                            'rounded-xl px-4 py-3 text-sm font-medium transition-all flex-1',
                                            useDesigner
                                                ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25'
                                                : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50'
                                        ]"
                                    >
                                        Designer avansat
                                    </button>
                                </div>

                                <!-- Samples Option -->
                                <div v-if="useSamples && hasSamples" class="space-y-4">
                                    <!-- Name and Date Inputs -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Numele (ex: Ana & Mihai)</label>
                                            <input
                                                type="text"
                                                v-model="form.qr_card_sample_names"
                                                placeholder="Ana & Mihai"
                                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Data evenimentului</label>
                                            <input
                                                type="text"
                                                v-model="form.qr_card_sample_date"
                                                placeholder="12 August 2026"
                                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                                            />
                                        </div>
                                    </div>

                                    <!-- Samples Grid -->
                                    <div v-if="samplesForEventType.length > 0" class="space-y-3">
                                        <p class="text-sm text-slate-600">Alege un model pentru {{ eventTypes[form.event_type] || 'evenimentul tău' }}:</p>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                            <div
                                                v-for="sample in samplesForEventType"
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
                                                        :alt="sample.id"
                                                        class="w-full aspect-[85/55] object-cover"
                                                    />
                                                    <!-- Fullscreen overlay icon -->
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="p-2 bg-white">
                                                    <button
                                                        type="button"
                                                        @click="selectSample(sample)"
                                                        :class="[
                                                            'w-full py-1.5 px-3 text-xs font-medium rounded-lg transition-colors',
                                                            selectedSampleId === sample.id
                                                                ? 'bg-emerald-500 text-white'
                                                                : 'bg-violet-100 text-violet-700 hover:bg-violet-200'
                                                        ]"
                                                    >
                                                        {{ selectedSampleId === sample.id ? '✓ Selectat' : 'Selectează' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected Sample Preview -->
                                    <div v-if="selectedSample" class="p-4 bg-emerald-50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="text-sm font-medium text-emerald-800">
                                                Ai selectat o variantă
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

                                    <!-- Size Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Dimensiune cartonaș:</label>
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                v-for="size in designerSizes"
                                                :key="size.id"
                                                type="button"
                                                @click="selectPredesignSize(size)"
                                                class="px-3 py-2 rounded-lg text-sm font-medium transition-all"
                                                :class="[
                                                    selectedPredesignSize === size.id
                                                        ? 'bg-violet-600 text-white shadow-md'
                                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                                ]"
                                            >
                                                {{ size.name }}
                                                <span class="text-xs opacity-75 ml-1">({{ size.width }}×{{ size.height }}mm)</span>
                                            </button>
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
                                                    class="aspect-[85/55] p-2 flex items-center gap-1 overflow-hidden"
                                                    :style="template.background.type === 'gradient'
                                                        ? { background: `linear-gradient(${template.background.direction}, ${template.background.colors[0]}, ${template.background.colors[1]})` }
                                                        : { backgroundColor: template.background.color }"
                                                >
                                                    <div class="flex-1 min-w-0 flex flex-col items-center justify-center gap-0.5">
                                                        <div
                                                            class="text-[9px] font-semibold truncate max-w-full px-1"
                                                            :style="{ color: template.textColor, fontFamily: template.primaryFont }"
                                                        >
                                                            {{ predesignNames || 'Ana & Mihai' }}
                                                        </div>
                                                        <div
                                                            class="text-[6px] truncate max-w-full px-1"
                                                            :style="{ color: template.accentColor, fontFamily: template.secondaryFont }"
                                                        >
                                                            {{ predesignDate || '12 August 2026' }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0 flex flex-col items-center gap-0.5">
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

                                    <!-- Confirmation -->
                                    <div v-if="selectedPredesignId" class="p-4 bg-emerald-50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-emerald-800">Design selectat</p>
                                                <p class="text-xs text-emerald-600 truncate">{{ selectedPredesign?.name }}</p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="openDesigner"
                                                class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition-colors"
                                            >
                                                Previzualizare
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Designer Option -->
                                <div v-else-if="useDesigner" class="space-y-4">
                                    <!-- Confirmation (if has design) -->
                                    <div v-if="hasCustomDesign" class="p-4 bg-emerald-50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-emerald-800">Design personalizat creat</p>
                                                <p class="text-xs text-emerald-600">{{ cardDesign.dimensions.width }}×{{ cardDesign.dimensions.height }} {{ cardDesign.dimensions.unit }}</p>
                                            </div>
                                            <button
                                                type="button"
                                                @click="openDesigner"
                                                class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition-colors"
                                            >
                                                Editează
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Open Designer Button -->
                                    <button
                                        v-else
                                        type="button"
                                        @click="openDesigner"
                                        class="w-full p-5 border-2 border-dashed border-violet-300 rounded-xl text-center hover:border-violet-400 hover:bg-violet-50/50 transition-all group"
                                    >
                                        <div class="w-10 h-10 mx-auto rounded-full bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        <p class="mt-2 text-sm font-semibold text-violet-600">Deschide Designer-ul</p>
                                        <p class="mt-1 text-xs text-slate-500">Creează un design unic</p>
                                    </button>
                                </div>

                                <!-- Card Design Error -->
                                <div v-if="cardDesignError" class="p-4 bg-red-50 rounded-xl">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-red-700">{{ cardDesignError }}</span>
                                    </div>
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

                <!-- Price Summary -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Sumar Pret</h2>
                        <p class="mt-1 text-sm text-slate-500">Pretul estimat in functie de selectiile tale.</p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            <div
                                v-for="(item, index) in priceBreakdown.items"
                                :key="index"
                                class="flex items-center justify-between text-sm"
                            >
                                <div class="flex-1">
                                    <span class="text-slate-700">{{ item.label }}</span>
                                    <span v-if="item.quantity > 1" class="text-slate-500 ml-1">
                                        ({{ item.quantity }} x {{ item.unitPrice }} lei)
                                    </span>
                                </div>
                                <span class="font-medium text-slate-900">{{ item.subtotal.toFixed(2) }} lei</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-semibold text-slate-900">Total estimat</span>
                                <span class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ priceBreakdown.total.toFixed(2) }} lei
                                </span>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                * Pretul final poate varia in functie de cerintele specifice. Te vom contacta pentru confirmare.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 p-6">
                    <div class="flex items-start gap-x-4">
                        <input
                            id="terms_accepted"
                            type="checkbox"
                            v-model="form.terms_accepted"
                            class="mt-1 h-5 w-5 rounded border-slate-300 text-violet-600 focus:ring-violet-500"
                            required
                        />
                        <div class="flex-1">
                            <label for="terms_accepted" class="block text-sm font-medium text-slate-900 cursor-pointer">
                                Accept termenii si conditiile *
                            </label>
                            <p class="mt-1 text-sm text-slate-500">
                                Sunt de acord cu
                                <a href="#" class="text-violet-600 hover:text-violet-700 underline">termenii si conditiile</a>
                                si cu
                                <a href="#" class="text-violet-600 hover:text-violet-700 underline">politica de confidentialitate</a>.
                            </p>
                            <p v-if="form.errors.terms_accepted" class="mt-2 text-sm text-red-600">{{ form.errors.terms_accepted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guarantee -->
                <div class="rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 p-6 ring-1 ring-emerald-200">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-emerald-900 mb-1">Garanție 100% satisfacție</h3>
                            <p class="text-sm text-emerald-700">Dacă nu ești mulțumit de serviciu, îți returnăm banii integral. Fără întrebări, fără bătăi de cap.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                    <Link
                        href="/"
                        class="order-2 sm:order-1 rounded-xl px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                    >
                        Anulează
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing || !form.terms_accepted"
                        class="order-1 sm:order-2 w-full sm:w-auto inline-flex items-center justify-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
                    >
                        <svg v-if="form.processing" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Rezervă acum →
                    </button>
                </div>
            </form>

            <!-- FAQ Section -->
            <div class="mt-16 mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 text-center mb-8">Întrebări frecvente</h2>
                <div class="space-y-4">
                    <details class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                        <summary class="flex items-center justify-between cursor-pointer p-5 font-medium text-slate-900">
                            Ce este inclus în pachetul standard?
                            <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-slate-600">
                            Pachetul standard include: galerie foto privată pentru evenimentul tău, cod QR unic, stocare nelimitată pentru poze, acces 12 luni la galerie, și suport tehnic. Cartonașele QR printate se comandă separat.
                        </div>
                    </details>
                    <details class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                        <summary class="flex items-center justify-between cursor-pointer p-5 font-medium text-slate-900">
                            Invitații trebuie să instaleze vreo aplicație?
                            <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-slate-600">
                            Nu! Invitații doar scanează codul QR cu telefonul și încarcă pozele direct din browser. Funcționează pe orice telefon, fără instalare.
                        </div>
                    </details>
                    <details class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                        <summary class="flex items-center justify-between cursor-pointer p-5 font-medium text-slate-900">
                            Când primesc cartonașele QR?
                            <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-slate-600">
                            Cartonașele sunt livrate cu 5-7 zile înainte de eveniment. Pentru comenzi urgente, te rugăm să ne contactezi pentru opțiuni de livrare expresă.
                        </div>
                    </details>
                    <details class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                        <summary class="flex items-center justify-between cursor-pointer p-5 font-medium text-slate-900">
                            Pot personaliza designul cartonașelor?
                            <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 text-sm text-slate-600">
                            Da! Poți alege dintr-o varietate de designuri pre-făcute sau poți folosi designer-ul nostru avansat pentru a crea un design unic. Adaugi numele, data, și alegi culorile care se potrivesc temei evenimentului.
                        </div>
                    </details>
                </div>
            </div>
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
                    <header class="bg-white border-b border-gray-200 px-3 sm:px-4 py-2 sm:py-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                                <button
                                    type="button"
                                    @click="closeDesigner"
                                    class="flex-shrink-0 p-1 text-gray-500 hover:text-gray-700 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <h1 class="text-sm sm:text-lg font-semibold text-gray-900 truncate">
                                    Designer Cartonas QR
                                </h1>
                            </div>

                            <div class="flex items-center gap-1 sm:gap-3 flex-shrink-0">
                                <button
                                    type="button"
                                    class="hidden sm:block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors"
                                    @click="handleExport"
                                >
                                    Export PDF
                                </button>
                                <button
                                    type="button"
                                    class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition-colors whitespace-nowrap"
                                    @click="saveDesign"
                                >
                                    Salveaza
                                </button>
                            </div>
                        </div>
                    </header>

                    <!-- Designer Content - Desktop Layout -->
                    <div class="hidden md:flex h-[calc(100vh-57px)]">
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

                    <!-- Designer Content - Mobile Layout -->
                    <div class="md:hidden flex flex-col h-[calc(100vh-49px)]">
                        <!-- Canvas Area - Scrollable -->
                        <div class="flex-1 overflow-auto bg-gradient-to-b from-gray-50 to-gray-100">
                            <!-- Zoom Controls - Fixed at top -->
                            <div class="sticky top-0 z-10 flex items-center justify-center gap-2 py-2 bg-white/80 backdrop-blur-sm border-b border-gray-200">
                                <button
                                    type="button"
                                    @click="mobileCanvasScale = Math.max(1, mobileCanvasScale - 0.5)"
                                    class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg text-gray-600 active:bg-gray-200"
                                    :disabled="mobileCanvasScale <= 1"
                                    :class="{ 'opacity-40': mobileCanvasScale <= 1 }"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <span class="text-xs font-medium text-gray-600 min-w-[4rem] text-center">Zoom {{ Math.round(mobileCanvasScale * 100 / 3) }}%</span>
                                <button
                                    type="button"
                                    @click="mobileCanvasScale = Math.min(3.5, mobileCanvasScale + 0.5)"
                                    class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg text-gray-600 active:bg-gray-200"
                                    :disabled="mobileCanvasScale >= 3.5"
                                    :class="{ 'opacity-40': mobileCanvasScale >= 3.5 }"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Scrollable Card Container -->
                            <div class="p-4 overflow-x-auto">
                                <div class="flex flex-col items-center min-w-fit">
                                    <!-- Card Preview -->
                                    <div class="bg-white p-3 rounded-xl shadow-lg">
                                        <CardCanvas
                                            ref="cardCanvasRef"
                                            :design="cardDesign"
                                            :qr-url="qrUrl"
                                            :scale="mobileCanvasScale"
                                            :selected-element-id="selectedElementId"
                                            :interactive="true"
                                            @select-element="handleSelectElement"
                                        />
                                    </div>

                                    <!-- Dimensions & Hint -->
                                    <div class="text-center mt-3">
                                        <div class="text-xs text-gray-500">
                                            {{ cardDesign.dimensions.width }} × {{ cardDesign.dimensions.height }} {{ cardDesign.dimensions.unit }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-1">
                                            Atinge un element pentru a-l edita
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Toolbar Toggle Button -->
                        <button
                            type="button"
                            @click="showMobileToolbar = !showMobileToolbar"
                            class="flex items-center justify-center gap-2 py-3.5 bg-white border-t border-gray-200 text-sm font-medium shadow-[0_-2px_10px_rgba(0,0,0,0.1)]"
                            :class="showMobileToolbar ? 'text-violet-600' : 'text-gray-700'"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            {{ showMobileToolbar ? 'Ascunde opțiuni' : 'Personalizează' }}
                            <svg
                                class="w-4 h-4 transition-transform"
                                :class="{ 'rotate-180': showMobileToolbar }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <!-- Mobile Bottom Sheet Toolbar -->
                        <Transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="translate-y-full"
                            enter-to-class="translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="translate-y-0"
                            leave-to-class="translate-y-full"
                        >
                            <div
                                v-if="showMobileToolbar"
                                class="bg-white border-t border-gray-200 max-h-[60vh] overflow-hidden flex flex-col"
                            >
                                <DesignToolbar
                                    :design="cardDesign"
                                    :templates="designerTemplates"
                                    :fonts="designerFonts"
                                    :sizes="designerSizes"
                                    :selected-element-id="selectedElementId"
                                    :selected-template="selectedTemplateId"
                                    :mobile="true"
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
                            </div>
                        </Transition>
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
