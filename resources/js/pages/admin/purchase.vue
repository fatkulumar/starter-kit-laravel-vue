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
                <PlusIcon @click="purchaseStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />

                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="purchaseStore.handleSearch" v-model="purchaseStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>

            <PurchasesTable :purchases="purchaseStore.purchases" :current-page="purchaseStore.pagination?.current_page"
                :per-page="purchaseStore.pagination?.per_page" :purchase-store="purchaseStore" />
            <Pagination v-if="purchaseStore.pagination" :links="purchaseStore.pagination.links"
                @page-change="purchaseStore.handlePageChange" />
        </div>

        <Modal :show="purchaseStore.showModal" @close="purchaseStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ purchaseStore.form.id ? 'Form Edit Pembayaran' : 'Form Tambah Pembayaran' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="purchaseStore.handleSave">
                <img v-if="purchaseStore.proof" :src="purchaseStore.proof" alt="Preview"
                    class="max-w-xs rounded shadow w-20" />

                <div class="flex justify-between items-center">
                    <div>
                        Nama: {{  purchaseStore.form.name }}
                        Email : {{  purchaseStore.form.email }}
                    </div>
                    <div>
                        Tryout: {{  purchaseStore.form.tryout_title }}
                        Biaya : {{  purchaseStore.formatRupiah(purchaseStore.form.amount) }}
                    </div>
                </div>

                <!-- <Label for="banner">Banner</Label>
                <Input id="banner" type="file" autofocus :tabindex="1" accept="image/jpg,image/jpeg,image/png"
                    autocomplete="banner" @change="purchaseStore.handleFileChange" />
                <InputError :message="purchaseStore.error?.banner?.[0]" />

                <Label for="title">Judul</Label>
                <Input id="title" type="text" required :tabindex="2" autocomplete="title"
                    v-model="purchaseStore.form.title" placeholder="Masukkan Judul" />
                <InputError :message="purchaseStore.error?.title?.[0]" />

                <Label for="description">Deskripsi</Label>
                <Textarea class="w-full" id="description" required :tabindex="3" autocomplete="description"
                    v-model="purchaseStore.form.description" placeholder="Masukkan Placeholder" />
                <InputError :message="purchaseStore.error?.description?.[0]" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div>
                            <Label for="round-1">Jumlah Babak</Label>

                            <div class="mt-2">
                                1:
                                <input id="round-1" type="radio" name="round" required :tabindex="6"
                                    autocomplete="round" value="1" v-model="purchaseStore.form.round" />
                            </div>

                            <div>
                                2:
                                <input id="round-2" type="radio" name="round" required :tabindex="6"
                                    autocomplete="round" value="2" v-model="purchaseStore.form.round" />
                            </div>
                        </div>
                    </div>

                     <div class="space-y-3">
                        <Label for="start_time">Is Publish</Label>
                        <input class="w-6 h-6" type="checkbox"
                            :tabindex="4" autocomplete="start_time" v-model="purchaseStore.form.is_publish"
                            :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.start_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="start_time">Mulai</Label>
                        <Input :readonly="!!purchaseStore.form.start_time" id="start_time" type="datetime-local" required
                            :tabindex="4" autocomplete="start_time" v-model="purchaseStore.form.start_time"
                            :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.start_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="end_time">Selesai</Label>
                        <Input id="end_time" type="datetime-local" required :tabindex="5" autocomplete="end_time"
                            v-model="purchaseStore.form.end_time" :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.end_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="registration_deadline">Batas Registrasi</Label>
                        <Input id="registration_deadline" type="datetime-local" required :tabindex="6"
                            autocomplete="registration_deadline" v-model="purchaseStore.form.registration_deadline"
                            :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.registration_deadline?.[0]" />
                    </div>

                    <div class="space-y-3" v-if="purchaseStore.form.round == 2">
                        <Label for="preliminary_date">Babak Penyisihan</Label>
                        <Input id="preliminary_date" type="datetime-local" required :tabindex="7"
                            autocomplete="preliminary_date" v-model="purchaseStore.form.preliminary_date"
                            :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.preliminary_date?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="final_date">Final</Label>
                        <Input id="final_date" type="datetime-local" required :tabindex="8" autocomplete="final_date"
                            v-model="purchaseStore.form.final_date" :min="todayDateTime" />
                        <InputError :message="purchaseStore.error?.final_date?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="is_online">Is Online</Label>
                        <Select id="is_online" v-model="purchaseStore.form.is_online" :tabindex="9"
                            :options="purchaseStore.isOnlineOptions" placeholder="Pilih Status" class="mt-2" />
                        <InputError :message="purchaseStore.error?.is_online?.[0]" />
                    </div>
                </div>

                <Label for="whatsapp_group_link">Whatsapp Group</Label>
                <Input id="whatsapp_group_link" type="text" required :tabindex="10" autocomplete="whatsapp_group_link"
                    v-model="purchaseStore.form.whatsapp_group_link" placeholder="Masukkan Judul" />
                <InputError :message="purchaseStore.error?.whatsapp_group_link?.[0]" />

                <Label for="guidebook_link">Panduan Teknis</Label>
                <Input id="guidebook_link" type="text" required :tabindex="11" autocomplete="guidebook_link"
                    v-model="purchaseStore.form.guidebook_link" placeholder="Masukkan Judul" />
                <InputError :message="purchaseStore.error?.guidebook_link?.[0]" />

                <Label for="location">Lokasi</Label>
                <Textarea class="w-full" id="location" required :tabindex="12" autocomplete="location"
                    v-model="purchaseStore.form.location" placeholder="Masukkan Placeholder" />
                <InputError :message="purchaseStore.error?.location?.[0]" />

                <div class="flex justify-between items-center">
                    <Label for="link_zoom">Link Zoom</Label>
                    <div class="flex items-center gap-1">
                        <label for="webinar">Webinar</label>
                        <input id="webinar" @click="purchaseStore.checkWebinar" v-model="purchaseStore.isWebinar"
                            type="checkbox" />
                    </div>
                </div>
                <Textarea :readonly="!purchaseStore.isWebinar" class="w-full" id="link_zoom" :tabindex="13"
                    autocomplete="link_zoom" v-model="purchaseStore.form.link_zoom"
                    :placeholder="!purchaseStore.isWebinar ? 'Tidak Perlu Diisi' : 'Masukkan Placeholder'" />
                <InputError :message="purchaseStore.error?.link_zoom?.[0]" />

                <Label for="quota">Kuota</Label>
                <Input id="quota" type="number" required :tabindex="14" autocomplete="quota"
                    v-model="purchaseStore.form.quota" />
                <InputError :message="purchaseStore.error?.quota?.[0]" /> -->

                <Button type="submit" class="mt-2 w-full" :tabindex="15" :disabled="purchaseStore.isLoading">
                    <LoaderCircle v-if="purchaseStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal>
    </AppLayout>
</template>