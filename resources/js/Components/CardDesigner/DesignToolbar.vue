<script setup>
import { ref, computed, watch } from 'vue'
import SizeSelector from './SizeSelector.vue'
import ColorPicker from './ColorPicker.vue'
import TextEditor from './TextEditor.vue'
import QrStyleEditor from './QrStyleEditor.vue'
import TemplateCard from './TemplateCard.vue'

const props = defineProps({
    design: {
        type: Object,
        required: true,
    },
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
    selectedElementId: {
        type: String,
        default: null,
    },
    selectedTemplate: {
        type: String,
        default: null,
    },
})

const emit = defineEmits([
    'update-dimensions',
    'update-background',
    'update-text-content',
    'update-text-style',
    'update-text-position',
    'update-qr-position',
    'update-qr-size',
    'update-qr-colors',
    'apply-template',
    'save',
    'export',
])

const activeTab = ref('templates')

const tabs = [
    { id: 'templates', label: 'Template', icon: 'grid' },
    { id: 'size', label: 'Dimensiune', icon: 'resize' },
    { id: 'background', label: 'Fundal', icon: 'palette' },
    { id: 'elements', label: 'Elemente', icon: 'text' },
]

const selectedElement = computed(() => {
    if (!props.selectedElementId) return null
    if (props.selectedElementId === 'qr') return 'qr'
    return props.design.textElements.find(el => el.id === props.selectedElementId)
})

// Switch to elements tab when an element is selected
watch(() => props.selectedElementId, (newId) => {
    if (newId) {
        activeTab.value = 'elements'
    }
})

const handleSizeChange = (size) => {
    emit('update-dimensions', size.id, size.width, size.height, size.unit)
}

const handleBackgroundChange = (background) => {
    emit('update-background', background)
}

const handleTemplateSelect = (template) => {
    emit('apply-template', template)
}
</script>

<template>
    <div class="design-toolbar h-full flex flex-col bg-white">
        <!-- Tabs -->
        <div class="flex border-b border-gray-200 px-2">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                type="button"
                class="flex-1 py-3 px-2 text-xs font-medium transition-colors border-b-2 -mb-px"
                :class="[
                    activeTab === tab.id
                        ? 'border-violet-500 text-violet-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700'
                ]"
                @click="activeTab = tab.id"
            >
                {{ tab.label }}
            </button>
        </div>

        <!-- Tab Content -->
        <div class="flex-1 overflow-y-auto p-4">
            <!-- Templates Tab -->
            <div v-if="activeTab === 'templates'" class="space-y-4">
                <p class="text-sm text-gray-600">
                    Alege un template sau începe de la zero
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <TemplateCard
                        v-for="template in templates"
                        :key="template.id"
                        :template="template"
                        :selected="selectedTemplate === template.id"
                        @select="handleTemplateSelect"
                    />
                </div>
            </div>

            <!-- Size Tab -->
            <div v-else-if="activeTab === 'size'">
                <SizeSelector
                    :model-value="design.dimensions.preset"
                    :sizes="sizes"
                    @change="handleSizeChange"
                />
            </div>

            <!-- Background Tab -->
            <div v-else-if="activeTab === 'background'">
                <ColorPicker
                    :model-value="design.background"
                    label="Culoare fundal"
                    @update:model-value="handleBackgroundChange"
                />
            </div>

            <!-- Elements Tab -->
            <div v-else-if="activeTab === 'elements'" class="space-y-6">
                <!-- Instructions -->
                <div v-if="!selectedElementId" class="text-center py-8">
                    <div class="text-gray-400 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">
                        Click pe un element din preview pentru a-l edita
                    </p>
                </div>

                <!-- Text Element Editor -->
                <div v-else-if="selectedElement && selectedElement !== 'qr'">
                    <TextEditor
                        :element="selectedElement"
                        :fonts="fonts"
                        @update-content="(id, content) => emit('update-text-content', id, content)"
                        @update-style="(id, key, value) => emit('update-text-style', id, key, value)"
                        @update-position="(id, x, y) => emit('update-text-position', id, x, y)"
                    />
                </div>

                <!-- QR Code Editor -->
                <div v-else-if="selectedElementId === 'qr'">
                    <QrStyleEditor
                        :qr-code="design.qrCode"
                        @update-position="(x, y) => emit('update-qr-position', x, y)"
                        @update-size="(size) => emit('update-qr-size', size)"
                        @update-colors="(fg, bg) => emit('update-qr-colors', fg, bg)"
                    />
                </div>

                <!-- Quick Access to All Elements -->
                <div class="border-t pt-4 mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Toate elementele</h4>
                    <div class="space-y-1">
                        <button
                            v-for="element in design.textElements"
                            :key="element.id"
                            type="button"
                            class="w-full p-2 text-left text-sm rounded-lg transition-colors"
                            :class="[
                                selectedElementId === element.id
                                    ? 'bg-violet-50 text-violet-700'
                                    : 'hover:bg-gray-50 text-gray-600'
                            ]"
                            @click="emit('select-element', element.id)"
                        >
                            <span class="font-medium">{{ element.id === 'names' ? 'Nume' : element.id === 'date' ? 'Dată' : element.id === 'message' ? 'Mesaj' : 'Instrucțiuni' }}</span>
                            <span class="text-gray-400 ml-2">{{ element.content }}</span>
                        </button>
                        <button
                            type="button"
                            class="w-full p-2 text-left text-sm rounded-lg transition-colors"
                            :class="[
                                selectedElementId === 'qr'
                                    ? 'bg-violet-50 text-violet-700'
                                    : 'hover:bg-gray-50 text-gray-600'
                            ]"
                            @click="emit('select-element', 'qr')"
                        >
                            <span class="font-medium">Cod QR</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="border-t border-gray-200 p-4 space-y-2">
            <button
                type="button"
                class="w-full px-4 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-lg hover:from-violet-700 hover:to-purple-700 transition-colors font-medium"
                @click="emit('save')"
            >
                Salvează Design
            </button>
            <button
                type="button"
                class="w-full px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                @click="emit('export')"
            >
                Exportă PDF / PNG
            </button>
        </div>
    </div>
</template>
