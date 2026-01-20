import { ref, watch } from 'vue'
import QRCode from 'qrcode'

export function useQrGenerator(initialUrl = '') {
    const qrDataUrl = ref('')
    const qrUrl = ref(initialUrl)
    const isGenerating = ref(false)
    const error = ref(null)

    async function generateQr(url, options = {}) {
        if (!url) {
            qrDataUrl.value = ''
            return
        }

        isGenerating.value = true
        error.value = null

        const defaultOptions = {
            width: 256,
            margin: 0,
            color: {
                dark: options.fgColor || '#000000',
                light: options.bgColor || '#FFFFFF',
            },
            errorCorrectionLevel: 'M',
        }

        try {
            const mergedOptions = { ...defaultOptions, ...options }

            // Handle transparent background
            if (mergedOptions.color.light === 'transparent') {
                mergedOptions.color.light = '#FFFFFF00'
            }

            qrDataUrl.value = await QRCode.toDataURL(url, mergedOptions)
            qrUrl.value = url
        } catch (err) {
            console.error('QR generation error:', err)
            error.value = 'Eroare la generarea codului QR'
            qrDataUrl.value = ''
        } finally {
            isGenerating.value = false
        }
    }

    async function generateSvg(url, options = {}) {
        if (!url) return ''

        const defaultOptions = {
            margin: 0,
            color: {
                dark: options.fgColor || '#000000',
                light: options.bgColor || '#FFFFFF',
            },
            errorCorrectionLevel: 'M',
        }

        try {
            const mergedOptions = { ...defaultOptions, ...options }

            if (mergedOptions.color.light === 'transparent') {
                mergedOptions.color.light = '#FFFFFF00'
            }

            return await QRCode.toString(url, { ...mergedOptions, type: 'svg' })
        } catch (err) {
            console.error('QR SVG generation error:', err)
            return ''
        }
    }

    // Auto-generate on URL change
    watch(qrUrl, async (newUrl) => {
        if (newUrl) {
            await generateQr(newUrl)
        }
    }, { immediate: true })

    return {
        qrDataUrl,
        qrUrl,
        isGenerating,
        error,
        generateQr,
        generateSvg,
    }
}
