import { ref, computed, reactive, watch } from 'vue'

const defaultDesign = {
    dimensions: {
        preset: 'business_card',
        width: 85,
        height: 55,
        unit: 'mm',
    },
    background: {
        type: 'solid',
        color: '#FFFFFF',
        gradient: {
            colors: ['#FFFFFF', '#F5F5F5'],
            direction: '135deg',
        },
    },
    textElements: [
        {
            id: 'names',
            content: 'Ana & Mihai',
            position: { x: 30, y: 30 },
            style: {
                fontFamily: 'Playfair Display',
                fontSize: 20,
                fontWeight: 'bold',
                color: '#2C2C2C',
                textAlign: 'center',
            },
        },
        {
            id: 'date',
            content: '12 August 2026',
            position: { x: 30, y: 50 },
            style: {
                fontFamily: 'Montserrat',
                fontSize: 10,
                fontWeight: 'normal',
                color: '#666666',
                textAlign: 'center',
            },
        },
        {
            id: 'message',
            content: 'Încarcă amintirile tale!',
            position: { x: 30, y: 70 },
            style: {
                fontFamily: 'Montserrat',
                fontSize: 9,
                fontWeight: 'normal',
                color: '#888888',
                textAlign: 'center',
            },
        },
        {
            id: 'instructions',
            content: 'Scanează codul QR',
            position: { x: 75, y: 80 },
            style: {
                fontFamily: 'Montserrat',
                fontSize: 7,
                fontWeight: 'normal',
                color: '#999999',
                textAlign: 'center',
            },
        },
    ],
    qrCode: {
        position: { x: 75, y: 45 },
        size: 30,
        style: {
            fgColor: '#000000',
            bgColor: 'transparent',
        },
    },
}

export function useCardDesign(initialDesign = null) {
    const design = reactive(initialDesign ? JSON.parse(JSON.stringify(initialDesign)) : JSON.parse(JSON.stringify(defaultDesign)))
    const selectedElementId = ref(null)
    const undoStack = ref([])
    const redoStack = ref([])
    const isDirty = ref(false)

    const selectedElement = computed(() => {
        if (!selectedElementId.value) return null
        return design.textElements.find(el => el.id === selectedElementId.value)
    })

    function saveState() {
        undoStack.value.push(JSON.stringify(design))
        if (undoStack.value.length > 50) {
            undoStack.value.shift()
        }
        redoStack.value = []
        isDirty.value = true
    }

    function undo() {
        if (undoStack.value.length === 0) return
        redoStack.value.push(JSON.stringify(design))
        const previousState = JSON.parse(undoStack.value.pop())
        Object.assign(design, previousState)
    }

    function redo() {
        if (redoStack.value.length === 0) return
        undoStack.value.push(JSON.stringify(design))
        const nextState = JSON.parse(redoStack.value.pop())
        Object.assign(design, nextState)
    }

    function setDimensions(preset, width, height, unit = 'mm') {
        saveState()
        design.dimensions = { preset, width, height, unit }
    }

    function setBackgroundType(type) {
        saveState()
        design.background.type = type
    }

    function setBackgroundColor(color) {
        saveState()
        design.background.color = color
    }

    function setGradientColors(colors) {
        saveState()
        design.background.gradient.colors = colors
    }

    function setGradientDirection(direction) {
        saveState()
        design.background.gradient.direction = direction
    }

    function selectElement(id) {
        selectedElementId.value = id
    }

    function deselectElement() {
        selectedElementId.value = null
    }

    function updateTextContent(id, content) {
        saveState()
        const element = design.textElements.find(el => el.id === id)
        if (element) {
            element.content = content
        }
    }

    function updateTextStyle(id, styleKey, value) {
        saveState()
        const element = design.textElements.find(el => el.id === id)
        if (element) {
            element.style[styleKey] = value
        }
    }

    function updateTextPosition(id, x, y) {
        saveState()
        const element = design.textElements.find(el => el.id === id)
        if (element) {
            element.position = { x, y }
        }
    }

    function updateQrPosition(x, y) {
        saveState()
        design.qrCode.position = { x, y }
    }

    function updateQrSize(size) {
        saveState()
        design.qrCode.size = size
    }

    function updateQrColors(fgColor, bgColor) {
        saveState()
        design.qrCode.style.fgColor = fgColor
        design.qrCode.style.bgColor = bgColor
    }

    function applyTemplate(template) {
        saveState()

        // Apply background
        if (template.background.type === 'gradient') {
            design.background = {
                type: 'gradient',
                color: template.background.colors[0],
                gradient: {
                    colors: template.background.colors,
                    direction: template.background.direction,
                },
            }
        } else {
            design.background = {
                type: 'solid',
                color: template.background.color,
                gradient: design.background.gradient,
            }
        }

        // Apply fonts and colors to text elements
        design.textElements.forEach(element => {
            if (element.id === 'names') {
                element.style.fontFamily = template.primaryFont
                element.style.color = template.textColor
            } else if (element.id === 'date') {
                element.style.fontFamily = template.secondaryFont
                element.style.color = template.accentColor
            } else if (element.id === 'message') {
                element.style.fontFamily = template.secondaryFont
                element.style.color = template.accentColor
            } else if (element.id === 'instructions') {
                element.style.fontFamily = template.secondaryFont
                element.style.color = template.accentColor
            }
        })

        // Update QR color based on background
        const isDarkBg = template.background.type === 'solid' &&
            isColorDark(template.background.color)
        design.qrCode.style.fgColor = isDarkBg ? '#FFFFFF' : '#000000'
    }

    function isColorDark(color) {
        const hex = color.replace('#', '')
        const r = parseInt(hex.substr(0, 2), 16)
        const g = parseInt(hex.substr(2, 2), 16)
        const b = parseInt(hex.substr(4, 2), 16)
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255
        return luminance < 0.5
    }

    function resetToDefault() {
        saveState()
        Object.assign(design, JSON.parse(JSON.stringify(defaultDesign)))
    }

    function exportDesign() {
        return JSON.parse(JSON.stringify(design))
    }

    function importDesign(newDesign) {
        saveState()
        Object.assign(design, JSON.parse(JSON.stringify(newDesign)))
    }

    return {
        design,
        selectedElementId,
        selectedElement,
        isDirty,
        canUndo: computed(() => undoStack.value.length > 0),
        canRedo: computed(() => redoStack.value.length > 0),
        setDimensions,
        setBackgroundType,
        setBackgroundColor,
        setGradientColors,
        setGradientDirection,
        selectElement,
        deselectElement,
        updateTextContent,
        updateTextStyle,
        updateTextPosition,
        updateQrPosition,
        updateQrSize,
        updateQrColors,
        applyTemplate,
        resetToDefault,
        exportDesign,
        importDesign,
        undo,
        redo,
    }
}

export { defaultDesign }
