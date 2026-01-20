<script setup>
import { computed } from 'vue'

const props = defineProps({
    template: {
        type: Object,
        required: true,
    },
    selected: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['select'])

const backgroundStyle = computed(() => {
    const { background } = props.template
    if (background.type === 'gradient') {
        return {
            background: `linear-gradient(${background.direction}, ${background.colors[0]}, ${background.colors[1]})`,
        }
    }
    return {
        backgroundColor: background.color,
    }
})

const textStyle = computed(() => ({
    color: props.template.textColor,
    fontFamily: `"${props.template.primaryFont}", sans-serif`,
}))

const accentStyle = computed(() => ({
    color: props.template.accentColor,
    fontFamily: `"${props.template.secondaryFont}", sans-serif`,
}))

const qrStyle = computed(() => {
    const isDark = props.template.background.type === 'solid' &&
        isColorDark(props.template.background.color)
    return {
        backgroundColor: isDark ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.1)',
    }
})

function isColorDark(color) {
    const hex = color.replace('#', '')
    const r = parseInt(hex.substr(0, 2), 16)
    const g = parseInt(hex.substr(2, 2), 16)
    const b = parseInt(hex.substr(4, 2), 16)
    return (0.299 * r + 0.587 * g + 0.114 * b) / 255 < 0.5
}
</script>

<template>
    <button
        type="button"
        class="template-card w-full rounded-xl overflow-hidden transition-all duration-200 hover:scale-[1.02]"
        :class="[
            selected
                ? 'ring-2 ring-violet-500 ring-offset-2 shadow-lg'
                : 'ring-1 ring-gray-200 hover:ring-gray-300 shadow-sm hover:shadow-md'
        ]"
        @click="emit('select', template)"
    >
        <!-- Card Preview -->
        <div
            class="aspect-[85/55] p-2 flex items-center"
            :style="backgroundStyle"
        >
            <!-- Left side - Text -->
            <div class="flex-1 flex flex-col items-center justify-center gap-0.5">
                <div
                    class="text-[10px] font-semibold truncate max-w-full"
                    :style="textStyle"
                >
                    Ana & Mihai
                </div>
                <div
                    class="text-[6px] truncate max-w-full"
                    :style="accentStyle"
                >
                    12 August 2026
                </div>
                <div
                    class="text-[5px] truncate max-w-full mt-0.5"
                    :style="accentStyle"
                >
                    Încarcă amintirile!
                </div>
            </div>
            <!-- Right side - QR -->
            <div class="flex flex-col items-center gap-0.5">
                <div
                    class="w-7 h-7 rounded flex items-center justify-center"
                    :style="qrStyle"
                >
                    <span class="text-[5px]" :style="{ color: template.textColor }">QR</span>
                </div>
                <div
                    class="text-[4px]"
                    :style="accentStyle"
                >
                    Scanează
                </div>
            </div>
        </div>

        <!-- Template Info -->
        <div class="p-2 bg-white border-t border-gray-100">
            <div class="text-sm font-medium text-gray-800 truncate">
                {{ template.name }}
            </div>
            <div class="text-xs text-gray-500">
                {{ template.style }}
            </div>
        </div>
    </button>
</template>

<style scoped>
.template-card:focus {
    outline: none;
}
</style>
