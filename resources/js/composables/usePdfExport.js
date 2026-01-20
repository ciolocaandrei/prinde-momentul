import { ref } from 'vue'
import { jsPDF } from 'jspdf'
import html2canvas from 'html2canvas'

export function usePdfExport() {
    const isExporting = ref(false)
    const exportError = ref(null)

    async function exportToPdf(elementRef, design, filename = 'qr-card') {
        if (!elementRef) {
            exportError.value = 'Element de referință lipsă'
            return null
        }

        isExporting.value = true
        exportError.value = null

        try {
            const element = elementRef.$el || elementRef

            // Create canvas from HTML element
            const canvas = await html2canvas(element, {
                scale: 3, // High quality for print
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                logging: false,
            })

            // Calculate PDF dimensions in mm
            const { width, height } = design.dimensions
            const pxToMm = 0.264583 // 1px = 0.264583mm at 96 DPI

            // Create PDF with custom size
            const pdf = new jsPDF({
                orientation: width > height ? 'landscape' : 'portrait',
                unit: 'mm',
                format: [width, height],
            })

            // Add canvas as image
            const imgData = canvas.toDataURL('image/png', 1.0)
            pdf.addImage(imgData, 'PNG', 0, 0, width, height)

            // Generate filename with timestamp
            const timestamp = new Date().toISOString().slice(0, 10)
            const fullFilename = `${filename}-${timestamp}.pdf`

            // Save the PDF
            pdf.save(fullFilename)

            isExporting.value = false
            return fullFilename
        } catch (error) {
            console.error('Export PDF error:', error)
            exportError.value = 'Eroare la exportul PDF. Încercați din nou.'
            isExporting.value = false
            return null
        }
    }

    async function exportToPng(elementRef, filename = 'qr-card') {
        if (!elementRef) {
            exportError.value = 'Element de referință lipsă'
            return null
        }

        isExporting.value = true
        exportError.value = null

        try {
            const element = elementRef.$el || elementRef

            const canvas = await html2canvas(element, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                logging: false,
            })

            // Create download link
            const link = document.createElement('a')
            const timestamp = new Date().toISOString().slice(0, 10)
            link.download = `${filename}-${timestamp}.png`
            link.href = canvas.toDataURL('image/png', 1.0)
            link.click()

            isExporting.value = false
            return link.download
        } catch (error) {
            console.error('Export PNG error:', error)
            exportError.value = 'Eroare la exportul imagine. Încercați din nou.'
            isExporting.value = false
            return null
        }
    }

    async function getPreviewDataUrl(elementRef) {
        if (!elementRef) return null

        try {
            const element = elementRef.$el || elementRef
            const canvas = await html2canvas(element, {
                scale: 1,
                useCORS: true,
                allowTaint: true,
                backgroundColor: null,
                logging: false,
            })
            return canvas.toDataURL('image/png')
        } catch (error) {
            console.error('Preview generation error:', error)
            return null
        }
    }

    return {
        isExporting,
        exportError,
        exportToPdf,
        exportToPng,
        getPreviewDataUrl,
    }
}
