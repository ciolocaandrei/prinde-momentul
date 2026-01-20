<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    fonts: {
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
    label: {
        type: String,
        default: 'Font',
    },
})

const emit = defineEmits(['update:modelValue'])

const selectedFont = computed(() => {
    return props.fonts.find(f => f.id === props.modelValue) || props.fonts[0]
})

const selectFont = (fontId) => {
    emit('update:modelValue', fontId)
}

const getFontStyle = (font) => {
    return {
        fontFamily: `"${font.id}", sans-serif`,
    }
}

const groupedFonts = computed(() => {
    const groups = {}
    props.fonts.forEach(font => {
        if (!groups[font.category]) {
            groups[font.category] = []
        }
        groups[font.category].push(font)
    })
    return groups
})
</script>

<template>
    <div class="font-selector">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ label }}
        </label>

        <div class="space-y-3">
            <!-- Font Preview -->
            <div
                class="p-3 bg-gray-50 rounded-lg text-center"
                :style="getFontStyle(selectedFont)"
            >
                <span class="text-xl">{{ selectedFont.name }}</span>
            </div>

            <!-- Font List -->
            <div class="space-y-2 max-h-48 overflow-y-auto">
                <div v-for="(fonts, category) in groupedFonts" :key="category">
                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1 px-1">
                        {{ category }}
                    </div>
                    <div class="space-y-1">
                        <button
                            v-for="font in fonts"
                            :key="font.id"
                            type="button"
                            class="w-full p-2 text-left rounded-lg border transition-all"
                            :class="[
                                modelValue === font.id
                                    ? 'border-violet-500 bg-violet-50'
                                    : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'
                            ]"
                            :style="getFontStyle(font)"
                            @click="selectFont(font.id)"
                        >
                            <span class="text-lg">{{ font.name }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
