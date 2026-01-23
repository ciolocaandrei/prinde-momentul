<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    sizes: {
        type: Array,
        default: () => [
            { id: 'business_card', name: 'Business Card', width: 85, height: 55, unit: 'mm' },
            { id: 'a7', name: 'A7', width: 74, height: 105, unit: 'mm' },
            { id: 'a6', name: 'A6', width: 105, height: 148, unit: 'mm' },
        ],
    },
})

const emit = defineEmits(['update:modelValue', 'change'])

const selectedSize = computed(() => {
    return props.sizes.find(s => s.id === props.modelValue) || props.sizes[0]
})

const selectSize = (size) => {
    emit('update:modelValue', size.id)
    emit('change', size)
}

const getAspectRatio = (size) => {
    return size.width / size.height
}

const getPreviewStyle = (size) => {
    const maxHeight = 60
    const aspectRatio = getAspectRatio(size)

    if (aspectRatio >= 1) {
        // Landscape or square
        const width = maxHeight * aspectRatio
        return {
            width: `${Math.min(width, 80)}px`,
            height: `${maxHeight / (width > 80 ? width / 80 : 1)}px`,
        }
    } else {
        // Portrait
        return {
            width: `${maxHeight * aspectRatio}px`,
            height: `${maxHeight}px`,
        }
    }
}
</script>

<template>
    <div class="size-selector">
        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-3">
            Dimensiune cartonaș
        </label>

        <div class="grid grid-cols-3 gap-1.5 sm:gap-3">
            <button
                v-for="size in sizes"
                :key="size.id"
                type="button"
                class="size-option p-2 sm:p-3 rounded-lg border-2 transition-all duration-200 flex flex-col items-center gap-1 sm:gap-2"
                :class="[
                    modelValue === size.id
                        ? 'border-violet-500 bg-violet-50 ring-2 ring-violet-200'
                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'
                ]"
                @click="selectSize(size)"
            >
                <!-- Size Preview -->
                <div
                    class="size-preview bg-white border border-gray-300 rounded shadow-sm flex items-center justify-center"
                    :style="getPreviewStyle(size)"
                >
                    <span class="text-[6px] sm:text-[8px] text-gray-400">QR</span>
                </div>

                <!-- Size Info -->
                <div class="text-center">
                    <div
                        class="text-[10px] sm:text-sm font-medium"
                        :class="modelValue === size.id ? 'text-violet-700' : 'text-gray-700'"
                    >
                        {{ size.name }}
                    </div>
                    <div class="text-[8px] sm:text-xs text-gray-500">
                        {{ size.width }}×{{ size.height }}{{ size.unit }}
                    </div>
                </div>
            </button>
        </div>

        <!-- Selected size info -->
        <div class="mt-2 sm:mt-3 p-1.5 sm:p-2 bg-gray-50 rounded-lg text-center">
            <span class="text-[10px] sm:text-sm text-gray-600">
                Selectat: <strong>{{ selectedSize.name }}</strong>
                <span class="hidden sm:inline">({{ selectedSize.width }} × {{ selectedSize.height }} {{ selectedSize.unit }})</span>
            </span>
        </div>
    </div>
</template>

<style scoped>
.size-option:focus {
    outline: none;
    ring: 2px;
    ring-color: theme('colors.violet.500');
}
</style>
