<script setup lang="ts">
import PurchasesTable from '@/components/admin/purchase/PurchasesTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { usePurchaseStore } from '@/stores/admin/purchaseStore';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/select.vue';
import { LoaderCircle } from 'lucide-vue-next';
import Textarea from '@/components/ui/textarea/Textarea.vue';

const purchaseStore = usePurchaseStore()

onMounted(() => {
    purchaseStore.fetchPurchases()
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pembayaran',
        href: 'purchase',
    },
];

const todayDateTime = computed(() => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
});

</script>

<template>

    <Head title="Pembayaran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <!-- <PlusIcon @click="purchaseStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" /> -->

                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="purchaseStore.handleSearch" v-model="purchaseStore.searchQuery"
                        type="text" placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>

            <PurchasesTable :purchases="purchaseStore.purchases" :current-page="purchaseStore.pagination?.current_page"
                :per-page="purchaseStore.pagination?.per_page" :purchase-store="purchaseStore" />
            <Pagination v-if="purchaseStore.pagination" :links="purchaseStore.pagination.links"
                @page-change="purchaseStore.handlePageChange" />
        </div>

        <Modal :show="purchaseStore.showModalConfirm" @close="purchaseStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ purchaseStore.form.id ? 'Form Edit Pembayaran' :
                'Form Tambah Pembayaran' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="purchaseStore.handleSave">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><span class="font-semibold">Nama:</span> {{ purchaseStore.form.name }}</p>
                        <p><span class="font-semibold">Email:</span> {{ purchaseStore.form.email }}</p>
                    </div>
                    <div>
                        <p><span class="font-semibold">Tryout:</span> {{ purchaseStore.form.tryout_title }}</p>
                        <p><span class="font-semibold">Biaya:</span> {{
                            purchaseStore.formatRupiah(purchaseStore.form.amount) }}</p>
                    </div>
                </div>

                <div class="mt-2">
                    <p><span class="font-semibold">Status:</span> {{ purchaseStore.form.status }}</p>
                </div>


                <div class="flex flex-col">
                    <div class="overflow-auto max-h-64 max-w-full border rounded p-2">
                        <div class="flex space-x-2">
                            <div v-for="(item, index) in purchaseStore.proof" :key="index" class="flex-shrink-1">
                                <img :src="item.proof_url" class="h-40 w-auto object-contain border rounded" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-2">
                    <Button @click="purchaseStore.handleConfirmPurchase(purchaseStore.form.name, purchaseStore.form.id)" type="button"
                        class="mt-2 w-full" :tabindex="15" :disabled="purchaseStore.isLoading">
                        <LoaderCircle v-if="purchaseStore.isLoading" class="h-4 w-4 animate-spin" />
                        Konfirm
                    </Button>
                    <Button type="button" class="mt-2 w-full bg-red-600 text-white" :tabindex="15"
                        @click="purchaseStore.handleRejectPurchase(purchaseStore.form.name, purchaseStore.form.id)">
                        <LoaderCircle v-if="purchaseStore.isLoading" class="h-4 w-4 animate-spin" />
                        Reject
                    </Button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>