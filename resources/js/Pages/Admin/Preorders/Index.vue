<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    preorders: Object,
    eventTypes: Object,
    statuses: Object,
});

const statusColors = {
    pending: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    confirmed: 'bg-blue-50 text-blue-700 ring-blue-600/20',
    completed: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    cancelled: 'bg-red-50 text-red-700 ring-red-600/20',
};

const deletePreorder = (preorder) => {
    if (confirm(`Esti sigur ca vrei sa stergi precomanda de la "${preorder.contact_name}"?`)) {
        router.delete(route('admin.preorders.destroy', preorder.id));
    }
};
</script>

<template>
    <Head title="Precomenzi" />

    <AdminLayout>
        <template #header>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Precomenzi</h1>
                <p class="mt-1 text-sm text-slate-500">Gestioneaza toate precomenzile primite</p>
            </div>
        </template>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Contact
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Tip Eveniment
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Data Eveniment
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Optiuni
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Data
                        </th>
                        <th scope="col" class="relative py-4 pl-3 pr-6">
                            <span class="sr-only">Actiuni</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <tr v-for="preorder in preorders.data" :key="preorder.id" class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-5 pl-6 pr-3">
                            <div class="flex items-center gap-x-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white text-sm font-bold">
                                    {{ preorder.contact_name.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ preorder.contact_name }}</div>
                                    <div class="text-sm text-slate-500">{{ preorder.contact_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                {{ eventTypes[preorder.event_type] || preorder.event_type }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <div v-if="preorder.event_date" class="flex items-center gap-x-2 text-sm text-slate-700">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ new Date(preorder.event_date).toLocaleDateString('ro-RO', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                            </div>
                            <span v-else class="text-sm text-slate-400">-</span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <div class="flex items-center gap-x-2">
                                <span v-if="preorder.wants_print_package" class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700">
                                    Print
                                </span>
                                <span v-if="preorder.wants_qr_card" class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">
                                    QR ({{ preorder.qr_card_quantity }})
                                </span>
                                <span v-if="!preorder.wants_print_package && !preorder.wants_qr_card" class="text-sm text-slate-400">-</span>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5">
                            <span
                                :class="[
                                    statusColors[preorder.status] || 'bg-slate-100 text-slate-600 ring-slate-500/10',
                                    'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset'
                                ]"
                            >
                                {{ statuses[preorder.status] || preorder.status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm text-slate-500">
                            {{ new Date(preorder.created_at).toLocaleDateString('ro-RO', { month: 'short', day: 'numeric', year: 'numeric' }) }}
                        </td>
                        <td class="relative whitespace-nowrap py-5 pl-3 pr-6 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-x-3">
                                <Link
                                    :href="route('admin.preorders.show', preorder.id)"
                                    class="text-rose-500 hover:text-rose-600 font-medium transition-colors"
                                >
                                    Vezi
                                </Link>
                                <button
                                    @click="deletePreorder(preorder)"
                                    class="text-red-500 hover:text-red-700 font-medium transition-colors"
                                >
                                    Sterge
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="preorders.data.length === 0">
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex h-16 w-16 mx-auto items-center justify-center rounded-full bg-slate-100">
                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-slate-900">Nicio precomanda inca</h3>
                            <p class="mt-2 text-sm text-slate-500">Precomenzile vor aparea aici cand cineva completeaza formularul.</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="preorders.links && preorders.last_page > 1" class="flex items-center justify-between border-t border-slate-200 bg-white px-6 py-4">
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-600">
                            Afisare <span class="font-semibold text-slate-900">{{ preorders.from }}</span> - <span class="font-semibold text-slate-900">{{ preorders.to }}</span> din <span class="font-semibold text-slate-900">{{ preorders.total }}</span> rezultate
                        </p>
                    </div>
                    <nav class="flex items-center gap-x-1">
                        <template v-for="link in preorders.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'bg-slate-900 text-white'
                                        : 'bg-white text-slate-700 hover:bg-slate-50 ring-1 ring-inset ring-slate-300',
                                    'relative inline-flex items-center px-3.5 py-2 text-sm font-medium rounded-lg transition-colors'
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="relative inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-400"
                                v-html="link.label"
                            />
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
