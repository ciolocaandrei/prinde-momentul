<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import QRCode from 'qrcode';

const props = defineProps({
    wedding: Object,
    uploadUrl: String,
    accessUrl: String,
    eventTypes: Object,
    clientPassword: String,
});

const eventTypeLabels = {
    nunta: 'Nuntă',
    majorat: 'Majorat',
    zi_de_nastere: 'Zi de Naștere',
    botez: 'Botez',
    altele: 'Alt Eveniment',
};

const editing = ref(false);
const copied = ref(false);
const accessLinkCopied = ref(false);
const credentialsCopied = ref(false);
const qrCodeUrl = ref('');

onMounted(async () => {
    qrCodeUrl.value = await QRCode.toDataURL(props.uploadUrl, {
        width: 256,
        margin: 2,
        color: { dark: '#1e293b', light: '#ffffff' }
    });
});

const downloadQrCode = async () => {
    const link = document.createElement('a');
    link.download = `qr-${props.wedding.couple_name.replace(/\s+/g, '-')}.png`;
    link.href = qrCodeUrl.value;
    link.click();
};

const form = useForm({
    couple_name: props.wedding.couple_name,
    event_type: props.wedding.event_type,
    event_date: props.wedding.event_date.split('T')[0],
    is_active: props.wedding.is_active,
});

const save = () => {
    form.put(route('admin.events.update', props.wedding.id), {
        onSuccess: () => {
            editing.value = false;
        },
    });
};

const regenerateCode = () => {
    if (confirm('Ești sigur? Linkul actual de upload va deveni invalid.')) {
        router.post(route('admin.events.regenerate-code', props.wedding.id));
    }
};

const copyUploadUrl = async () => {
    await navigator.clipboard.writeText(props.uploadUrl);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
};

const copyAccessLink = async () => {
    await navigator.clipboard.writeText(props.accessUrl);
    accessLinkCopied.value = true;
    setTimeout(() => {
        accessLinkCopied.value = false;
    }, 2000);
};

const regenerateAccessCode = () => {
    if (confirm('Ești sigur? Linkul actual de acces va deveni invalid.')) {
        router.post(route('admin.events.regenerate-access-code', props.wedding.id));
    }
};

const copyCredentials = async () => {
    const eventDate = new Date(props.wedding.event_date).toLocaleDateString('ro-RO', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    const message = `Bună ziua,

Vă transmitem datele de acces pentru platforma Prinde Momentul, unde veți putea vizualiza și descărca fotografiile de la evenimentul dumneavoastră.

📅 Eveniment: ${props.wedding.couple_name}
📆 Data: ${eventDate}

🔐 Link de acces (click pentru autentificare automată):
${props.accessUrl}

📸 Link pentru invitați:
${props.uploadUrl}

Distribuiți linkul de mai sus invitaților pentru a încărca fotografiile de la eveniment.

Cu drag,
Echipa Prinde Momentul`;

    await navigator.clipboard.writeText(message);
    credentialsCopied.value = true;
    setTimeout(() => {
        credentialsCopied.value = false;
    }, 2000);
};

const toggleActive = () => {
    router.put(route('admin.events.update', props.wedding.id), {
        is_active: !props.wedding.is_active,
    });
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const totalSize = computed(() => {
    return props.wedding.photos.reduce((sum, photo) => sum + photo.size, 0);
});

const deleteWedding = () => {
    if (confirm(`Ești sigur că vrei să ștergi "${props.wedding.couple_name}"? Toate fotografiile și contul asociat vor fi șterse permanent.`)) {
        router.delete(route('admin.events.destroy', props.wedding.id));
    }
};
</script>

<template>
    <Head :title="wedding.couple_name" />

    <AdminLayout>
        <template #header>
            <div>
                <nav class="flex items-center gap-x-2 text-sm">
                    <Link :href="route('admin.events.index')" class="text-slate-500 hover:text-slate-700 transition-colors">Evenimente</Link>
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-medium">{{ wedding.couple_name }}</span>
                </nav>
                <div class="mt-2 flex items-center gap-x-3">
                    <h1 class="text-2xl font-bold text-slate-900">{{ wedding.couple_name }}</h1>
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                        {{ eventTypeLabels[wedding.event_type] || wedding.event_type }}
                    </span>
                    <span
                        :class="[
                            wedding.is_active
                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                                : 'bg-slate-100 text-slate-600 ring-slate-500/10',
                            'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset'
                        ]"
                    >
                        {{ wedding.is_active ? 'Activ' : 'Inactiv' }}
                    </span>
                </div>
            </div>
            <button
                @click="deleteWedding"
                class="inline-flex items-center gap-x-2 rounded-xl bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 hover:bg-red-100 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Șterge
            </button>
        </template>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Event Details -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Detalii Eveniment</h2>
                        <button
                            v-if="!editing"
                            @click="editing = true"
                            class="text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors"
                        >
                            Editează
                        </button>
                    </div>

                    <form v-if="editing" @submit.prevent="save" class="px-6 py-6 space-y-5">
                        <div>
                            <label for="couple_name" class="block text-sm font-medium text-slate-700">Nume</label>
                            <input
                                id="couple_name"
                                type="text"
                                v-model="form.couple_name"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                            />
                        </div>
                        <div>
                            <label for="event_type" class="block text-sm font-medium text-slate-700">Tip Eveniment</label>
                            <select
                                id="event_type"
                                v-model="form.event_type"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                            >
                                <option v-for="(label, value) in eventTypes" :key="value" :value="value">
                                    {{ label }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="event_date" class="block text-sm font-medium text-slate-700">Data Evenimentului</label>
                            <input
                                id="event_date"
                                type="date"
                                v-model="form.event_date"
                                class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 text-sm"
                            />
                        </div>
                        <div class="flex items-center gap-x-3 pt-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:from-violet-700 hover:to-purple-700 transition-all"
                            >
                                Salvează
                            </button>
                            <button
                                type="button"
                                @click="editing = false"
                                class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                            >
                                Anulează
                            </button>
                        </div>
                    </form>

                    <dl v-else class="px-6 py-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-500">Tip Eveniment</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ eventTypeLabels[wedding.event_type] || wedding.event_type }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-500">Data Evenimentului</dt>
                            <dd class="text-sm font-medium text-slate-900">
                                {{ new Date(wedding.event_date).toLocaleDateString('ro-RO', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-500">Email Cont</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ wedding.user.email }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-500">Total Fotografii</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ wedding.photos.length }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-500">Spațiu Utilizat</dt>
                            <dd class="text-sm font-medium text-slate-900">{{ formatFileSize(totalSize) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Photo Gallery -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Fotografii ({{ wedding.photos.length }})</h2>
                        <Link
                            v-if="wedding.photos.length > 0"
                            :href="route('admin.events.gallery', wedding.id)"
                            class="inline-flex items-center gap-x-1.5 text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors"
                        >
                            Vezi Galeria
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                    <div class="p-6">
                        <div v-if="wedding.photos.length > 0" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            <div
                                v-for="photo in wedding.photos.slice(0, 10)"
                                :key="photo.id"
                                class="group relative aspect-square overflow-hidden rounded-xl bg-slate-100"
                            >
                                <img
                                    :src="photo.url"
                                    :alt="photo.original_name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-3 left-3 right-3">
                                        <p class="text-xs text-white truncate font-medium">{{ photo.uploaded_by_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="wedding.photos.length > 10" class="mt-4 text-center">
                            <Link
                                :href="route('admin.events.gallery', wedding.id)"
                                class="inline-flex items-center gap-x-2 text-sm font-medium text-violet-600 hover:text-violet-700 transition-colors"
                            >
                                +{{ wedding.photos.length - 10 }} alte fotografii
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </Link>
                        </div>
                        <div v-else-if="wedding.photos.length === 0" class="py-12 text-center">
                            <div class="flex h-14 w-14 mx-auto items-center justify-center rounded-full bg-slate-100">
                                <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">Nicio fotografie încărcată încă</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Upload Link -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Link Upload Invitați</h2>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-slate-500 mb-4">Distribuie acest link invitaților pentru a încărca fotografii direct.</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-x-2">
                                <input
                                    type="text"
                                    :value="uploadUrl"
                                    readonly
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-600"
                                />
                            </div>
                            <div class="flex gap-x-2">
                                <button
                                    @click="copyUploadUrl"
                                    class="flex-1 inline-flex items-center justify-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-violet-700 hover:to-purple-700 transition-all"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    {{ copied ? 'Copiat!' : 'Copiază Link' }}
                                </button>
                                <a
                                    :href="uploadUrl"
                                    target="_blank"
                                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                            <button
                                @click="regenerateCode"
                                class="w-full text-center text-sm text-red-500 hover:text-red-600 transition-colors"
                            >
                                Regenerează Link
                            </button>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">QR Code</h2>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-slate-500 mb-4">Scanează pentru a accesa pagina de upload.</p>
                        <div class="flex justify-center mb-4">
                            <img
                                v-if="qrCodeUrl"
                                :src="qrCodeUrl"
                                alt="QR Code"
                                class="rounded-xl border border-slate-200"
                            />
                        </div>
                        <button
                            @click="downloadQrCode"
                            class="w-full inline-flex items-center justify-center gap-x-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Descarcă QR Code
                        </button>
                    </div>
                </div>

                <!-- Client Access Link -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Link Acces Client</h2>
                    </div>
                    <div class="px-6 py-6">
                        <p class="text-sm text-slate-500 mb-4">Acest link permite autentificarea automată pentru client.</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-x-2">
                                <input
                                    type="text"
                                    :value="accessUrl"
                                    readonly
                                    class="block w-full rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-600"
                                />
                            </div>
                            <div class="flex gap-x-2">
                                <button
                                    @click="copyAccessLink"
                                    class="flex-1 inline-flex items-center justify-center gap-x-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-emerald-700 hover:to-teal-700 transition-all"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    {{ accessLinkCopied ? 'Copiat!' : 'Copiază Link' }}
                                </button>
                                <a
                                    :href="accessUrl"
                                    target="_blank"
                                    class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                            <button
                                @click="regenerateAccessCode"
                                class="w-full text-center text-sm text-red-500 hover:text-red-600 transition-colors"
                            >
                                Regenerează Link Acces
                            </button>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <button
                                @click="copyCredentials"
                                class="w-full inline-flex items-center justify-center gap-x-2 rounded-xl bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-violet-700 hover:to-purple-700 transition-all"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                {{ credentialsCopied ? 'Copiat!' : 'Copiază Mesaj Complet' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Upload Status -->
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-semibold text-slate-900">Status Upload</h2>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ wedding.is_active ? 'Upload Activat' : 'Upload Dezactivat' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ wedding.is_active ? 'Invitații pot încărca fotografii' : 'Încărcarea fotografiilor este oprită' }}
                                </p>
                            </div>
                            <button
                                @click="toggleActive"
                                :class="[
                                    wedding.is_active ? 'bg-violet-600' : 'bg-slate-200',
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2'
                                ]"
                            >
                                <span
                                    :class="[
                                        wedding.is_active ? 'translate-x-5' : 'translate-x-0',
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
                                    ]"
                                />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 text-white shadow-lg">
                    <h3 class="text-sm font-medium text-violet-100">Statistici Rapide</h3>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-3xl font-bold">{{ wedding.photos.length }}</p>
                            <p class="text-xs text-violet-200">Fotografii</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">{{ formatFileSize(totalSize) }}</p>
                            <p class="text-xs text-violet-200">Spațiu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
