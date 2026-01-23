<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
        // { type: 'solid'|'gradient', color: '#fff', gradient: { colors: [], direction: '' } }
    },
    label: {
        type: String,
        default: 'Culoare fundal',
    },
    showGradient: {
        type: Boolean,
        default: true,
    },
})

const emit = defineEmits(['update:modelValue'])

const presetColors = [
    '#FFFFFF', '#FFFEF7', '#F5F0E1', '#FAF6F1',
    '#FDF2F8', '#EDE9FE', '#F0F4F0', '#E8F4EA',
    '#1E3A5F', '#2C2C2C', '#4A3728', '#2D4A3E',
]

const gradientPresets = [
    { colors: ['#FFFEF7', '#F5F0E1'], direction: '135deg', name: 'Ivoire' },
    { colors: ['#FDF2F8', '#EDE9FE'], direction: '135deg', name: 'Blush' },
    { colors: ['#E8F4EA', '#D4E5D7'], direction: '135deg', name: 'Salvie' },
    { colors: ['#F0F4FF', '#E8EDFF'], direction: '135deg', name: 'Lavandă' },
]

const gradientDirections = [
    { value: '0deg', label: '↑' },
    { value: '45deg', label: '↗' },
    { value: '90deg', label: '→' },
    { value: '135deg', label: '↘' },
    { value: '180deg', label: '↓' },
    { value: '225deg', label: '↙' },
    { value: '270deg', label: '←' },
    { value: '315deg', label: '↖' },
]

const localType = ref(props.modelValue.type)
const localColor = ref(props.modelValue.color)
const localGradientColor1 = ref(props.modelValue.gradient?.colors?.[0] || '#FFFFFF')
const localGradientColor2 = ref(props.modelValue.gradient?.colors?.[1] || '#F5F5F5')
const localGradientDirection = ref(props.modelValue.gradient?.direction || '135deg')

const updateValue = () => {
    emit('update:modelValue', {
        type: localType.value,
        color: localColor.value,
        gradient: {
            colors: [localGradientColor1.value, localGradientColor2.value],
            direction: localGradientDirection.value,
        },
    })
}

const selectPresetColor = (color) => {
    localColor.value = color
    localType.value = 'solid'
    updateValue()
}

const selectGradientPreset = (preset) => {
    localGradientColor1.value = preset.colors[0]
    localGradientColor2.value = preset.colors[1]
    localGradientDirection.value = preset.direction
    localType.value = 'gradient'
    updateValue()
}

const setType = (type) => {
    localType.value = type
    updateValue()
}

watch([localColor, localGradientColor1, localGradientColor2, localGradientDirection], () => {
    updateValue()
})

const gradientPreviewStyle = computed(() => {
    return {
        background: `linear-gradient(${localGradientDirection.value}, ${localGradientColor1.value}, ${localGradientColor2.value})`,
    }
})
</script>

<template>
    <div class="color-picker">
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
            {{ label }}
        </label>

        <!-- Type Toggle -->
        <div v-if="showGradient" class="flex rounded-lg bg-gray-100 p-0.5 sm:p-1 mb-3 sm:mb-4">
            <button
                type="button"
                class="flex-1 py-1.5 sm:py-2 px-2 sm:px-3 text-[10px] sm:text-sm font-medium rounded-md transition-all"
                :class="localType === 'solid' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                @click="setType('solid')"
            >
                Culoare
            </button>
            <button
                type="button"
                class="flex-1 py-1.5 sm:py-2 px-2 sm:px-3 text-[10px] sm:text-sm font-medium rounded-md transition-all"
                :class="localType === 'gradient' ? 'bg-white shadow text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                @click="setType('gradient')"
            >
                Gradient
            </button>
        </div>

        <!-- Solid Color Picker -->
        <div v-if="localType === 'solid'" class="space-y-2 sm:space-y-3">
            <!-- Preset Colors -->
            <div class="grid grid-cols-6 gap-1.5 sm:gap-2">
                <button
                    v-for="color in presetColors"
                    :key="color"
                    type="button"
                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg border-2 transition-all hover:scale-110"
                    :class="localColor === color ? 'border-violet-500 ring-2 ring-violet-200' : 'border-gray-200'"
                    :style="{ backgroundColor: color }"
                    @click="selectPresetColor(color)"
                />
            </div>

            <!-- Custom Color Input -->
            <div class="flex items-center gap-2">
                <input
                    type="color"
                    v-model="localColor"
                    class="w-8 h-8 sm:w-10 sm:h-10 rounded cursor-pointer border-0"
                />
                <input
                    type="text"
                    v-model="localColor"
                    class="flex-1 px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    placeholder="#FFFFFF"
                />
            </div>
        </div>

        <!-- Gradient Picker -->
        <div v-else class="space-y-2 sm:space-y-3">
            <!-- Gradient Presets -->
            <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                <button
                    v-for="preset in gradientPresets"
                    :key="preset.name"
                    type="button"
                    class="h-8 sm:h-10 rounded-lg border-2 transition-all hover:scale-105"
                    :class="
                        localGradientColor1 === preset.colors[0] && localGradientColor2 === preset.colors[1]
                            ? 'border-violet-500 ring-2 ring-violet-200'
                            : 'border-gray-200'
                    "
                    :style="{ background: `linear-gradient(${preset.direction}, ${preset.colors[0]}, ${preset.colors[1]})` }"
                    :title="preset.name"
                    @click="selectGradientPreset(preset)"
                />
            </div>

            <!-- Custom Gradient Colors -->
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center gap-0.5 sm:gap-1">
                    <input
                        type="color"
                        v-model="localGradientColor1"
                        class="w-6 h-6 sm:w-8 sm:h-8 rounded cursor-pointer border-0"
                    />
                    <span class="text-[10px] sm:text-xs text-gray-500">Start</span>
                </div>
                <div
                    class="flex-1 h-5 sm:h-6 rounded"
                    :style="gradientPreviewStyle"
                />
                <div class="flex items-center gap-0.5 sm:gap-1">
                    <span class="text-[10px] sm:text-xs text-gray-500">End</span>
                    <input
                        type="color"
                        v-model="localGradientColor2"
                        class="w-6 h-6 sm:w-8 sm:h-8 rounded cursor-pointer border-0"
                    />
                </div>
            </div>

            <!-- Gradient Direction -->
            <div>
                <span class="text-[10px] sm:text-xs text-gray-500 mb-1 block">Direcție gradient</span>
                <div class="flex gap-0.5 sm:gap-1 flex-wrap">
                    <button
                        v-for="dir in gradientDirections"
                        :key="dir.value"
                        type="button"
                        class="w-6 h-6 sm:w-8 sm:h-8 rounded border text-xs sm:text-sm transition-all"
                        :class="
                            localGradientDirection === dir.value
                                ? 'border-violet-500 bg-violet-50 text-violet-700'
                                : 'border-gray-200 hover:border-gray-300'
                        "
                        @click="localGradientDirection = dir.value"
                    >
                        {{ dir.label }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
