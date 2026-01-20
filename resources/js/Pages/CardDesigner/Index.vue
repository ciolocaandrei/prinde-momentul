<script setup>
import { ref, computed, provide } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useCardDesign } from '@/composables/useCardDesign'
import { usePdfExport } from '@/composables/usePdfExport'
import CardCanvas from '@/Components/CardDesigner/CardCanvas.vue'
import DesignToolbar from '@/Components/CardDesigner/DesignToolbar.vue'
import ExportModal from '@/Components/CardDesigner/ExportModal.vue'

// Font imports
import '@fontsource-variable/playfair-display'
import '@fontsource-variable/cormorant'
import '@fontsource/great-vibes'
import '@fontsource/dancing-script'
import '@fontsource/montserrat/400.css'
import '@fontsource/montserrat/500.css'
import '@fontsource/montserrat/600.css'
import '@fontsource/montserrat/700.css'
import '@fontsource/lora/400.css'
import '@fontsource/lora/500.css'
import '@fontsource/lora/600.css'
import '@fontsource/lora/700.css'

const props = defineProps({
    templates: {
        type: Array,
        default: () => [],
    },
    fonts: {
        type: Array,
        default: () => [],
    },
    sizes: {
        type: Array,
        default: () => [],
    },
})

const {
    design,
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
} = useCardDesign()

const { isExporting, exportToPdf, exportToPng } = usePdfExport()

const cardCanvasRef = ref(null)
const showExportModal = ref(false)
const selectedTemplateId = ref(null)
const saveSuccess = ref(false)

const qrUrl = computed(() => {
    return 'https://nunta360.ro/upload/DEMO123'
})

const handleSelectElement = (id) => {
    if (id) {
        selectElement(id)
    } else {
        deselectElement()
    }
}

const handleUpdateDimensions = (preset, width, height, unit) => {
    setDimensions(preset, width, height, unit)
}

const handleUpdateBackground = (background) => {
    setBackgroundType(background.type)
    if (background.type === 'solid') {
        setBackgroundColor(background.color)
    } else {
        setGradientColors(background.gradient.colors)
        setGradientDirection(background.gradient.direction)
    }
}

const handleApplyTemplate = (template) => {
    selectedTemplateId.value = template.id
    applyTemplate(template)
}

const handleSave = () => {
    const designData = exportDesign()
    console.log('Design saved:', designData)
    saveSuccess.value = true
    setTimeout(() => {
        saveSuccess.value = false
    }, 2000)
}

const handleExport = () => {
    showExportModal.value = true
}

const handleExportPdf = async () => {
    if (cardCanvasRef.value?.canvasRef) {
        await exportToPdf(cardCanvasRef.value.canvasRef, design, 'qr-card')
        showExportModal.value = false
    }
}

const handleExportPng = async () => {
    if (cardCanvasRef.value?.canvasRef) {
        await exportToPng(cardCanvasRef.value.canvasRef, 'qr-card')
        showExportModal.value = false
    }
}
</script>

<template>
    <Head title="Card Designer - Nunta360" />

    <div class="card-designer-page min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-4 py-3">
            <div class="max-w-screen-2xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        href="/"
                        class="text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h1 class="text-lg font-semibold text-gray-900">
                        Designer Cartonaș QR
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Save Success Message -->
                    <transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-y-1"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 translate-y-1"
                    >
                        <span
                            v-if="saveSuccess"
                            class="text-sm text-green-600 bg-green-50 px-3 py-1.5 rounded-full"
                        >
                            Design salvat!
                        </span>
                    </transition>

                    <button
                        type="button"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors"
                        @click="handleSave"
                    >
                        Salvează
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 text-sm bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition-colors"
                        @click="handleExport"
                    >
                        Exportă
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex h-[calc(100vh-65px)]">
            <!-- Sidebar -->
            <aside class="w-80 flex-shrink-0 border-r border-gray-200 bg-white overflow-hidden">
                <DesignToolbar
                    :design="design"
                    :templates="templates"
                    :fonts="fonts"
                    :sizes="sizes"
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
                    @save="handleSave"
                    @export="handleExport"
                />
            </aside>

            <!-- Canvas Area -->
            <main class="flex-1 flex items-center justify-center p-8 bg-gray-100">
                <div class="relative">
                    <!-- Canvas Container -->
                    <div class="bg-white p-8 rounded-2xl shadow-xl">
                        <CardCanvas
                            ref="cardCanvasRef"
                            :design="design"
                            :qr-url="qrUrl"
                            :scale="3"
                            :selected-element-id="selectedElementId"
                            :interactive="true"
                            @select-element="handleSelectElement"
                        />
                    </div>

                    <!-- Size Indicator -->
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 text-xs text-gray-500">
                        {{ design.dimensions.width }} × {{ design.dimensions.height }} {{ design.dimensions.unit }}
                    </div>
                </div>
            </main>
        </div>

        <!-- Export Modal -->
        <ExportModal
            :show="showExportModal"
            :is-exporting="isExporting"
            :design="design"
            @close="showExportModal = false"
            @export-pdf="handleExportPdf"
            @export-png="handleExportPng"
        />
    </div>
</template>

<style scoped>
.card-designer-page {
    font-family: 'Inter', system-ui, sans-serif;
}
</style>
