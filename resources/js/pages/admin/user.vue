<script setup lang="ts">
import UsersTable from '@/components/admin/user/UsersTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useUserStore } from '@/stores/admin/userStore';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Select from '@/components/ui/select/select.vue';
import { LoaderCircle } from 'lucide-vue-next';

const userStore = useUserStore()

onMounted(() => {
    userStore.fetchUsers()
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>

    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <PlusIcon @click="userStore.showModal = true" class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />

                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="userStore.handleSearch" v-model="userStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>

            <UsersTable :users="userStore.users" :current-page="userStore.pagination?.current_page"
                :per-page="userStore.pagination?.per_page" :user-store="userStore" />
            <Pagination v-if="userStore.pagination" :links="userStore.pagination.links"
                @page-change="userStore.handlePageChange" />
        </div>

        <Modal :show="userStore.showModal" @close="userStore.handleCloseModal">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ userStore.form.id ? 'Form Edit User' : 'Form Tambah User' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="userStore.handleSave">
                <img v-if="userStore.previewPhoto" :src="userStore.previewPhoto" alt="Preview" class="max-w-xs rounded shadow w-20" />
                <Label for="photo">Foto</Label>
                <Input id="photo" type="file" autofocus :tabindex="1" accept="image/jpg,image/jpeg,image/png" autocomplete="photo"
                    @change="userStore.handleFileChange" />
                <InputError :message="userStore.error?.photo?.[0]" />

                <Label for="name">Nama</Label>
                <Input id="name" type="text" required autofocus :tabindex="2" autocomplete="name"
                    v-model="userStore.form.name" placeholder="Masukkan Nama" />
                <InputError :message="userStore.error?.name?.[0]" />

                <Label for="email">Emsil</Label>
                <Input id="email" type="email" required autofocus :tabindex="3" autocomplete="email"
                    v-model="userStore.form.email" placeholder="Masukkan Email" />
                <InputError :message="userStore.error?.email?.[0]" />

                <Label for="password">Password</Label>
                <Input id="password" type="password" required autofocus :tabindex="4" autocomplete="password"
                    v-model="userStore.form.password" placeholder="Masukkan Passsword" />
                <InputError :message="userStore.error?.password?.[0]" />

                <Label for="role">Role</Label>
                <Select id="role" v-model="userStore.form.role" :tabindex="5" :options="userStore.roleOptions"
                    placeholder="Pilih role" class="mt-2" />
                <InputError :message="userStore.error?.role?.[0]" />

                <Button type="submit" class="mt-2 w-full" tabindex="6" :disabled="userStore.isLoading">
                    <LoaderCircle v-if="userStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal>
    </AppLayout>
</template>