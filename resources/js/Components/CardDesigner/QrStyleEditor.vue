<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    qrCode: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['update-position', 'update-size', 'update-colors'])

const localPositionX = ref(props.qrCode.position.x)
const localPositionY = ref(props.qrCode.position.y)
const localSize = ref(props.qrCode.size)
const localFgColor = ref(props.qrCode.style.fgColor)
const localBgColor = ref(props.qrCode.style.bgColor)
const transparentBg = ref(props.qrCode.style.bgColor === 'transparent')

watch(() => props.qrCode, (newQr) => {
    localPositionX.value = newQr.position.x
    localPositionY.value = newQr.position.y
    localSize.value = newQr.size
    localFgColor.value = newQr.style.fgColor
    localBgColor.value = newQr.style.bgColor
    transparentBg.value = newQr.style.bgColor === 'transparent'
}, { deep: true })

const updatePosition = () => {
    emit('update-position', localPositionX.value, localPositionY.value)
}

const updateSize = () => {
    emit('update-size', localSize.value)
}

const updateColors = () => {
    const bgColor = transparentBg.value ? 'transparent' : localBgColor.value
    emit('update-colors', localFgColor.value, bgColor)
}

const toggleTransparentBg = () => {
    transparentBg.value = !transparentBg.value
    updateColors()
}

const presetFgColors = [
    '#000000', '#1E3A5F', '#2C2C2C', '#4A3728',
    '#2D4A3E', '#8B7355', '#C9A959', '#FFFFFF',
]
</script>

<template>
    <div class="qr-style-editor space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="font-medium text-gray-900">Cod QR</h4>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                QR Code
            </span>
        </div>

        <!-- Size -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">
                Mărime: {{ localSize }}mm
            </label>
            <input
                type="range"
                v-model.number="localSize"
                min="15"
                max="60"
                step="1"
                class="w-full accent-violet-500"
                @input="updateSize"
            />
            <div class="flex justify-between text-xs text-gray-500">
                <span>Mic</span>
                <span>Mare</span>
            </div>
        </div>

        <!-- QR Color -->
        <div>
            <label class="block text-sm text-gray-600 mb-2">Culoare QR</label>
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="color in presetFgColors"
                    :key="color"
                    type="button"
                    class="w-7 h-7 rounded border-2 transition-all hover:scale-110"
                    :class="localFgColor === color ? 'border-violet-500' : 'border-gray-200'"
                    :style="{ backgroundColor: color }"
                    @click="localFgColor = color; updateColors()"
                />
                <input
                    type="color"
                    v-model="localFgColor"
                    class="w-7 h-7 rounded cursor-pointer border-0"
                    @input="updateColors"
                />
            </div>
        </div>

        <!-- Background Color -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm text-gray-600">Fundal QR</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        v-model="transparentBg"
                        class="rounded border-gray-300 text-violet-600 focus:ring-violet-500"
                        @change="updateColors"
                    />
                    <span class="text-xs text-gray-500">Transparent</span>
                </label>
            </div>
            <div v-if="!transparentBg" class="flex items-center gap-2">
                <input
                    type="color"
                    v-model="localBgColor"
                    class="w-8 h-8 rounded cursor-pointer border-0"
                    @input="updateColors"
                />
                <input
                    type="text"
                    v-model="localBgColor"
                    class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    @input="updateColors"
                />
            </div>
            <div v-else class="p-2 bg-gray-50 rounded text-xs text-gray-500 text-center">
                Fundalul QR va fi transparent
            </div>
        </div>

        <!-- Position -->
        <div>
            <label class="block text-sm text-gray-600 mb-2">Poziție (%)</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500">X (orizontal)</label>
                    <input
                        type="range"
                        v-model.number="localPositionX"
                        min="10"
                        max="90"
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
                        min="10"
                        max="90"
                        class="w-full accent-violet-500"
                        @input="updatePosition"
                    />
                    <div class="text-xs text-center text-gray-500">{{ localPositionY }}%</div>
                </div>
            </div>
        </div>
    </div>
</template>
