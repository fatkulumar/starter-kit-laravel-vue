<script setup lang="ts">
import EventsTable from '@/components/admin/event/EventsTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useEventStore } from '@/stores/admin/eventStore';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/select.vue';
import { LoaderCircle } from 'lucide-vue-next';
import Textarea from '@/components/ui/textarea/Textarea.vue';

const eventStore = useEventStore()

onMounted(() => {
    eventStore.fetchEvents()
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>

    <Head title="Events" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <PlusIcon @click="eventStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />

                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="eventStore.handleSearch" v-model="eventStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>

            <EventsTable :events="eventStore.events" :current-page="eventStore.pagination?.current_page"
                :per-page="eventStore.pagination?.per_page" :event-store="eventStore" />
            <Pagination v-if="eventStore.pagination" :links="eventStore.pagination.links"
                @page-change="eventStore.handlePageChange" />
        </div>

        <Modal :show="eventStore.showModal" @close="eventStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ eventStore.form.id ? 'Form Edit Event' : 'Form Tambah Event' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="eventStore.handleSave">
                <img v-if="eventStore.previewBanner" :src="eventStore.previewBanner" alt="Preview"
                    class="max-w-xs rounded shadow w-20" />

                <Label for="banner">Banner</Label>
                <Input id="banner" type="file" autofocus :tabindex="1" accept="image/jpg,image/jpeg,image/png"
                    autocomplete="banner" @change="eventStore.handleFileChange" />
                <InputError :message="eventStore.error?.photo?.[0]" />

                <Label for="title">Judul</Label>
                <Input id="title" type="text" required :tabindex="2" autocomplete="title"
                    v-model="eventStore.form.title" placeholder="Masukkan Judul" />
                <InputError :message="eventStore.error?.title?.[0]" />

                <Label for="description">Deskripsi</Label>
                <Textarea class="w-full" id="description" required :tabindex="3" autocomplete="description"
                    v-model="eventStore.form.description" placeholder="Masukkan Placeholder" />
                <InputError :message="eventStore.error?.description?.[0]" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <Label for="start_time">Mulai</Label>
                        <Input id="start_time" type="datetime-local" required :tabindex="4" autocomplete="start_time"
                            v-model="eventStore.form.start_time" />
                        <InputError :message="eventStore.error?.start_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="end_time">Selesai</Label>
                        <Input id="end_time" type="datetime-local" required :tabindex="5" autocomplete="end_time"
                            v-model="eventStore.form.end_time" />
                        <InputError :message="eventStore.error?.end_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="registration_deadline">Batas Registrasi</Label>
                        <Input id="registration_deadline" type="datetime-local" required :tabindex="6"
                            autocomplete="registration_deadline" v-model="eventStore.form.registration_deadline" />
                        <InputError :message="eventStore.error?.registration_deadline?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="preliminary_date">Batas Pra-Acara</Label>
                        <Input id="preliminary_date" type="datetime-local" required :tabindex="7"
                            autocomplete="preliminary_date" v-model="eventStore.form.preliminary_date" />
                        <InputError :message="eventStore.error?.preliminary_date?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="final_date">Final</Label>
                        <Input id="final_date" type="datetime-local" required :tabindex="8" autocomplete="final_date"
                            v-model="eventStore.form.final_date" />
                        <InputError :message="eventStore.error?.final_date?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="is_online">Is Online</Label>
                        <Select id="is_online" v-model="eventStore.form.is_online" :tabindex="9"
                            :options="eventStore.isOnlineOptions" placeholder="Pilih Status" class="mt-2" />
                        <InputError :message="eventStore.error?.is_online?.[0]" />
                    </div>
                </div>

                <Label for="whatsapp_group_link">Whatsapp Group</Label>
                <Input id="whatsapp_group_link" type="text" required :tabindex="10" autocomplete="whatsapp_group_link"
                    v-model="eventStore.form.whatsapp_group_link" placeholder="Masukkan Judul" />
                <InputError :message="eventStore.error?.whatsapp_group_link?.[0]" />

                <Label for="guidebook_link">Panduan Teknis</Label>
                <Input id="guidebook_link" type="text" required :tabindex="11" autocomplete="guidebook_link"
                    v-model="eventStore.form.guidebook_link" placeholder="Masukkan Judul" />
                <InputError :message="eventStore.error?.guidebook_link?.[0]" />

                <Label for="location">Lokasi</Label>
                <Textarea class="w-full" id="location" required :tabindex="12" autocomplete="location"
                    v-model="eventStore.form.location" placeholder="Masukkan Placeholder" />
                <InputError :message="eventStore.error?.location?.[0]" />

                <Label for="link_zoom">Link Zoom</Label>
                <Textarea class="w-full" id="link_zoom" required :tabindex="13" autocomplete="link_zoom"
                    v-model="eventStore.form.link_zoom" placeholder="Masukkan Placeholder" />
                <InputError :message="eventStore.error?.link_zoom?.[0]" />

                <Label for="quota">Kuota</Label>
                <Input id="quota" type="number" required :tabindex="14" autocomplete="quota"
                    v-model="eventStore.form.quota" />
                <InputError :message="eventStore.error?.quota?.[0]" />

                <Button type="submit" class="mt-2 w-full" :tabindex="15" :disabled="eventStore.isLoading">
                    <LoaderCircle v-if="eventStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal>
    </AppLayout>
</template>