<script setup lang="ts">
import TryoutsTable from '@/components/admin/tryout/TryoutsTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useTryoutStore } from '@/stores/admin/tryoutStore';
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

const tryoutStore = useTryoutStore()

onMounted(() => {
    tryoutStore.form.event_id = props.event_id;
    tryoutStore.fetchTryouts(props.event_id);
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    event_id: {
        type: String,
        default: () => ''
    }
});

</script>

<template>

    <Head title="Tryouts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <PlusIcon @click="tryoutStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />
                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="tryoutStore.handleSearch(props.event_id)"
                        v-model="tryoutStore.searchQuery" type="text" placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>

            <TryoutsTable :tryouts="tryoutStore.tryouts" :current-page="tryoutStore.pagination?.current_page"
                :per-page="tryoutStore.pagination?.per_page" :tryout-store="tryoutStore" />
            <Pagination v-if="tryoutStore.pagination" :links="tryoutStore.pagination.links"
                @page-change="tryoutStore.handlePageChange" />
        </div>

        <Modal :show="tryoutStore.showModal" @close="tryoutStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ tryoutStore.form.id ? 'Form Edit Tryout' : 'Form Tambah Tryout' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="tryoutStore.handleSave">
                <img v-if="tryoutStore.previewThumbnail" :src="tryoutStore.previewThumbnail" alt="Preview"
                    class="max-w-xs rounded shadow w-20" />

                <Label for="thumbnail">Thumbnail</Label>
                <Input id="thumbnail" type="file" autofocus :tabindex="1" accept="image/jpg,image/jpeg,image/png"
                    autocomplete="thumbnail" @change="tryoutStore.handleFileChange" />
                <InputError :message="tryoutStore.error?.photo?.[0]" />

                <Label for="title">Judul</Label>
                <Input id="title" type="text" required :tabindex="2" autocomplete="title"
                    v-model="tryoutStore.form.title" placeholder="Masukkan Judul" />
                <InputError :message="tryoutStore.error?.title?.[0]" />

                <Label for="description">Deskripsi</Label>
                <Textarea class="w-full" id="description" required :tabindex="3" autocomplete="description"
                    v-model="tryoutStore.form.description" placeholder="Masukkan Placeholder" />
                <InputError :message="tryoutStore.error?.description?.[0]" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <Label for="start_time">Mulai</Label>
                        <Input id="start_time" type="datetime-local" required :tabindex="4" autocomplete="start_time"
                            v-model="tryoutStore.form.start_time" />
                        <InputError :message="tryoutStore.error?.start_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="end_time">Selesai</Label>
                        <Input id="end_time" type="datetime-local" required :tabindex="5" autocomplete="end_time"
                            v-model="tryoutStore.form.end_time" />
                        <InputError :message="tryoutStore.error?.end_time?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="is_active">Is Active</Label>
                        <Select id="is_active" v-model="tryoutStore.form.is_active" :tabindex="6"
                            :options="tryoutStore.isActiveOptions" placeholder="Pilih Status" class="mt-2" />
                        <InputError :message="tryoutStore.error?.is_active?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="is_locked">Is Locked</Label>
                        <Select id="is_locked" v-model="tryoutStore.form.is_locked" :tabindex="7"
                            :options="tryoutStore.isLockedOptions" placeholder="Pilih Status" class="mt-2" />
                        <InputError :message="tryoutStore.error?.is_locked?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="duration">Durasi Pengerjaan</Label>
                        <Input id="duration" type="number" required :tabindex="8" autocomplete="duration"
                            v-model="tryoutStore.form.duration" />
                        <InputError :message="tryoutStore.error?.duration?.[0]" />
                    </div>

                    <div class="space-y-3">
                        <Label for="price">Harga</Label>
                        <Input id="price" type="number" required :tabindex="9" autocomplete="price"
                            v-model="tryoutStore.form.price" />
                        <InputError :message="tryoutStore.error?.price?.[0]" />
                    </div>
                </div>

                <Label for="guide_link">Panduan Teknis</Label>
                <Input id="guide_link" type="text" required :tabindex="10" autocomplete="guide_link"
                    v-model="tryoutStore.form.guide_link" placeholder="Masukkan Link Panduan Teknis" />
                <InputError :message="tryoutStore.error?.guide_link?.[0]" />

                <Button type="submit" class="mt-2 w-full" :tabindex="11" :disabled="tryoutStore.isLoading">
                    <LoaderCircle v-if="tryoutStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal>
    </AppLayout>
</template>