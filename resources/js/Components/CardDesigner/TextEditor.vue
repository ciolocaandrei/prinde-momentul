<script setup>
import { ref, computed, watch } from 'vue'
import FontSelector from './FontSelector.vue'

const props = defineProps({
    element: {
        type: Object,
        required: true,
    },
    fonts: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['update-content', 'update-style', 'update-position'])

const elementLabels = {
    names: 'Numele cuplu',
    date: 'Data evenimentului',
    message: 'Mesaj',
    instructions: 'Instrucțiuni QR',
}

const localContent = ref(props.element.content)
const localFontFamily = ref(props.element.style.fontFamily)
const localFontSize = ref(props.element.style.fontSize)
const localFontWeight = ref(props.element.style.fontWeight)
const localColor = ref(props.element.style.color)
const localPositionX = ref(props.element.position.x)
const localPositionY = ref(props.element.position.y)

watch(() => props.element, (newEl) => {
    localContent.value = newEl.content
    localFontFamily.value = newEl.style.fontFamily
    localFontSize.value = newEl.style.fontSize
    localFontWeight.value = newEl.style.fontWeight
    localColor.value = newEl.style.color
    localPositionX.value = newEl.position.x
    localPositionY.value = newEl.position.y
}, { deep: true })

const updateContent = () => {
    emit('update-content', props.element.id, localContent.value)
}

const updateStyle = (key, value) => {
    emit('update-style', props.element.id, key, value)
}

const updatePosition = () => {
    emit('update-position', props.element.id, localPositionX.value, localPositionY.value)
}

const presetColors = [
    '#000000', '#2C2C2C', '#4A4A4A', '#666666',
    '#888888', '#AAAAAA', '#FFFFFF', '#C9A959',
    '#D4A5A5', '#7C9A8D', '#4A3728', '#1E3A5F',
]

const fontSizes = [8, 10, 12, 14, 16, 18, 20, 24, 28, 32, 36, 42, 48]
</script>

<template>
    <div class="text-editor space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="font-medium text-gray-900">
                {{ elementLabels[element.id] || element.id }}
            </h4>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                Text
            </span>
        </div>

        <!-- Content -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Conținut</label>
            <input
                type="text"
                v-model="localContent"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                @input="updateContent"
            />
        </div>

        <!-- Font Family -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Font</label>
            <select
                v-model="localFontFamily"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                @change="updateStyle('fontFamily', localFontFamily)"
            >
                <option
                    v-for="font in fonts"
                    :key="font.id"
                    :value="font.id"
                    :style="{ fontFamily: font.id }"
                >
                    {{ font.name }}
                </option>
            </select>
        </div>

        <!-- Font Size & Weight -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Mărime</label>
                <select
                    v-model.number="localFontSize"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    @change="updateStyle('fontSize', localFontSize)"
                >
                    <option v-for="size in fontSizes" :key="size" :value="size">
                        {{ size }}px
                    </option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Grosime</label>
                <select
                    v-model="localFontWeight"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    @change="updateStyle('fontWeight', localFontWeight)"
                >
                    <option value="normal">Normal</option>
                    <option value="medium">Medium</option>
                    <option value="semibold">Semibold</option>
                    <option value="bold">Bold</option>
                </select>
            </div>
        </div>

        <!-- Text Color -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Culoare text</label>
            <div class="flex items-center gap-2">
                <div class="grid grid-cols-6 gap-1 flex-1">
                    <button
                        v-for="color in presetColors"
                        :key="color"
                        type="button"
                        class="w-6 h-6 rounded border-2 transition-all hover:scale-110"
                        :class="localColor === color ? 'border-violet-500' : 'border-gray-200'"
                        :style="{ backgroundColor: color }"
                        @click="localColor = color; updateStyle('color', color)"
                    />
                </div>
                <input
                    type="color"
                    v-model="localColor"
                    class="w-8 h-8 rounded cursor-pointer border-0"
                    @input="updateStyle('color', localColor)"
                />
            </div>
        </div>

        <!-- Position -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Poziție (%)</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500">X (orizontal)</label>
                    <input
                        type="range"
                        v-model.number="localPositionX"
                        min="0"
                        max="100"
                        class="w-full accent-violet-500"
                        @input="updatePosition"
                    />
                    <div class="text-xs text-center text-gray-500">{{ localPositionX }}%</div>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Y (vertical)</label>
                    <input
                        type="range"
                        v-model.number="localPositionY"
                        min="0"
                        max="100"
                        class="w-full accent-violet-500"
                        @input="updatePosition"
                    />
                    <div class="text-xs text-center text-gray-500">{{ localPositionY }}%</div>
                </div>
            </div>
        </div>
    </div>
</template>
