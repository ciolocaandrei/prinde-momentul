<script setup>
import { computed, ref, watch, onMounted } from 'vue'
import { useQrGenerator } from '@/composables/useQrGenerator'

const props = defineProps({
    design: {
        type: Object,
        required: true,
    },
    qrUrl: {
        type: String,
        default: 'https://nunta360.ro/upload/ABC123',
    },
    scale: {
        type: Number,
        default: 3,
    },
    selectedElementId: {
        type: String,
        default: null,
    },
    interactive: {
        type: Boolean,
        default: true,
    },
})

const emit = defineEmits(['select-element', 'update-position'])

const { qrDataUrl, generateQr } = useQrGenerator(props.qrUrl)
const canvasRef = ref(null)

// Calculate pixel dimensions from mm (assuming 96 DPI display, but we scale up for quality)
const pxPerMm = computed(() => 3.78 * props.scale) // ~3.78 px per mm at 96 DPI

const canvasStyle = computed(() => {
    const { width, height } = props.design.dimensions
    return {
        width: `${width * pxPerMm.value}px`,
        height: `${height * pxPerMm.value}px`,
    }
})

const backgroundStyle = computed(() => {
    const { background } = props.design
    if (background.type === 'gradient') {
        const { colors, direction } = background.gradient
        return {
            background: `linear-gradient(${direction}, ${colors[0]}, ${colors[1]})`,
        }
    }
    return {
        backgroundColor: background.color,
    }
})

const getTextStyle = (element) => {
    const { style, position } = element
    return {
        position: 'absolute',
        left: `${position.x}%`,
        top: `${position.y}%`,
        transform: 'translate(-50%, -50%)',
        fontFamily: `"${style.fontFamily}", sans-serif`,
        fontSize: `${style.fontSize * props.scale}px`,
        fontWeight: style.fontWeight,
        color: style.color,
        textAlign: style.textAlign,
        whiteSpace: 'nowrap',
        cursor: props.interactive ? 'pointer' : 'default',
        userSelect: 'none',
    }
}

const qrStyle = computed(() => {
    const { qrCode } = props.design
    const size = qrCode.size * pxPerMm.value
    return {
        position: 'absolute',
        left: `${qrCode.position.x}%`,
        top: `${qrCode.position.y}%`,
        transform: 'translate(-50%, -50%)',
        width: `${size}px`,
        height: `${size}px`,
        cursor: props.interactive ? 'move' : 'default',
    }
})

const isSelected = (elementId) => {
    return props.selectedElementId === elementId
}

const handleElementClick = (elementId) => {
    if (props.interactive) {
        emit('select-element', elementId)
    }
}

const handleCanvasClick = (e) => {
    if (e.target === canvasRef.value && props.interactive) {
        emit('select-element', null)
    }
}

// Regenerate QR when colors change
watch(
    () => [props.design.qrCode.style.fgColor, props.design.qrCode.style.bgColor, props.qrUrl],
    async ([fgColor, bgColor, url]) => {
        await generateQr(url, { fgColor, bgColor })
    },
    { immediate: true }
)

defineExpose({
    canvasRef,
})
</script>

<template>
    <div
        ref="canvasRef"
        class="card-canvas relative overflow-hidden shadow-lg"
        :style="[canvasStyle, backgroundStyle]"
        @click="handleCanvasClick"
    >
        <!-- Text Elements -->
        <div
            v-for="element in design.textElements"
            :key="element.id"
            :style="getTextStyle(element)"
            :class="[
                'text-element transition-all duration-150',
                { 'ring-2 ring-violet-500 ring-offset-2': isSelected(element.id) }
            ]"
            @click.stop="handleElementClick(element.id)"
        >
            {{ element.content }}
        </div>

        <!-- QR Code -->
        <div
            :style="qrStyle"
            :class="[
                'qr-code transition-all duration-150',
                { 'ring-2 ring-violet-500 ring-offset-2': isSelected('qr') }
            ]"
            @click.stop="handleElementClick('qr')"
        >
            <img
                v-if="qrDataUrl"
                :src="qrDataUrl"
                alt="QR Code"
                class="w-full h-full object-contain"
            />
            <div
                v-else
                class="w-full h-full bg-gray-200 flex items-center justify-center text-xs text-gray-500"
            >
                QR
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-canvas {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    border-radius: 4px;
}

.text-element:hover {
    opacity: 0.9;
}
</style>
