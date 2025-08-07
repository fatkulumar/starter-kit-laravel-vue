<script setup lang="ts">
import SubtestsTable from '@/components/admin/subtest/SubtestsTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useSubtestStore } from '@/stores/admin/subtestStore';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';
import { Tryout } from '@/types/Tryout';

const props = withDefaults(
  defineProps<{
    tryout?: Partial<Tryout>,
  }>(),
  {
    tryout: () => ({}),
  }
);

const subtestStore = useSubtestStore()

onMounted(async() => {
    subtestStore.form.tryout_id = props.tryout.id ?? '';
    await subtestStore.fetchSubtest?.(props.tryout.id)
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Subtests',
        href: 'subtest',
    },
];

</script>

<template>

    <Head title="Subtests" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <PlusIcon @click="subtestStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />

                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="subtestStore.handleSearch" v-model="subtestStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <p class="text-center">Subtest: {{ props.tryout.title }}</p>
            <SubtestsTable :subtests="subtestStore.subtests" :current-page="subtestStore.pagination?.current_page"
                :per-page="subtestStore.pagination?.per_page" :subtest-store="subtestStore" />
            <Pagination v-if="subtestStore.pagination" :links="subtestStore.pagination.links"
                @page-change="subtestStore.handlePageChange" />
        </div>

        <Modal :show="subtestStore.showModal" @close="subtestStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ subtestStore.form.id ? 'Form Edit Event' : 'Form Tambah Event' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="subtestStore.handleSave">

                <Label for="title">Judul</Label>
                <Input id="title" type="text" autofocus required :tabindex="2" autocomplete="title"
                    v-model="subtestStore.form.title" placeholder="Masukkan Judul" />
                <InputError :message="subtestStore.error?.title?.[0]" />

                <Label for="amount_question">Jumlah Pertanyaan</Label>
                <Input id="amount_question" type="number" required :tabindex="14" autocomplete="amount_question"
                    v-model="subtestStore.form.amount_question" />
                <InputError :message="subtestStore.error?.amount_question?.[0]" />

                <Label for="amount_minutes">Jumlah Menit</Label>
                <Input id="amount_minutes" type="number" required :tabindex="14" autocomplete="amount_minutes"
                    v-model="subtestStore.form.amount_minutes" />
                <InputError :message="subtestStore.error?.amount_minutes?.[0]" />

                <Button type="submit" class="mt-2 w-full" :tabindex="15" :disabled="subtestStore.isLoading">
                    <LoaderCircle v-if="subtestStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal>
    </AppLayout>
</template>