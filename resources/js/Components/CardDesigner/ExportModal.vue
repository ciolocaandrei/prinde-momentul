<script setup>
import { ref, computed } from 'vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    isExporting: {
        type: Boolean,
        default: false,
    },
    design: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['close', 'export-pdf', 'export-png'])

const selectedFormat = ref('pdf')

const formatOptions = [
    {
        id: 'pdf',
        name: 'PDF',
        description: 'Ideal pentru printare la tipografie',
        icon: '📄',
    },
    {
        id: 'png',
        name: 'PNG',
        description: 'Imagine de înaltă calitate',
        icon: '🖼️',
    },
]

const dimensionInfo = computed(() => {
    const { dimensions } = props.design
    return `${dimensions.width} × ${dimensions.height} ${dimensions.unit}`
})

const handleExport = () => {
    if (selectedFormat.value === 'pdf') {
        emit('export-pdf')
    } else {
        emit('export-png')
    }
}
</script>

<template>
    <Modal :show="show" max-width="md" @close="emit('close')">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                    Exportă Design
                </h3>
                <button
                    type="button"
                    class="text-gray-400 hover:text-gray-500 p-1"
                    @click="emit('close')"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Format Selection -->
            <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                <label class="block text-xs sm:text-sm font-medium text-gray-700">
                    Alege formatul
                </label>
                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                    <button
                        v-for="format in formatOptions"
                        :key="format.id"
                        type="button"
                        class="p-3 sm:p-4 rounded-xl border-2 text-left transition-all"
                        :class="[
                            selectedFormat === format.id
                                ? 'border-violet-500 bg-violet-50'
                                : 'border-gray-200 hover:border-gray-300'
                        ]"
                        @click="selectedFormat = format.id"
                    >
                        <div class="text-xl sm:text-2xl mb-1 sm:mb-2">{{ format.icon }}</div>
                        <div class="text-sm sm:text-base font-medium text-gray-900">{{ format.name }}</div>
                        <div class="text-[10px] sm:text-xs text-gray-500 mt-0.5 sm:mt-1">{{ format.description }}</div>
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                <h4 class="text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">Detalii export</h4>
                <ul class="space-y-0.5 sm:space-y-1 text-xs sm:text-sm text-gray-600">
                    <li>Dimensiune: <strong>{{ dimensionInfo }}</strong></li>
                    <li>Calitate: <strong>Înaltă (300 DPI)</strong></li>
                    <li v-if="selectedFormat === 'pdf'">
                        Format: <strong>PDF ready for print</strong>
                    </li>
                    <li v-else>
                        Format: <strong>PNG transparent</strong>
                    </li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 sm:gap-3">
                <button
                    type="button"
                    class="flex-1 px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                    @click="emit('close')"
                >
                    Anulează
                </button>
                <button
                    type="button"
                    class="flex-1 px-3 sm:px-4 py-2 text-sm bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    :disabled="isExporting"
                    @click="handleExport"
                >
                    <svg
                        v-if="isExporting"
                        class="animate-spin h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ isExporting ? 'Se exportă...' : 'Descarcă' }}</span>
                </button>
            </div>
        </div>
    </Modal>
</template>
