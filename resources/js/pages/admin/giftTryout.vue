<script setup lang="ts">
import GiftTryoutTable from '@/components/admin/giftTryout/GiftTryoutTable.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useUserStore } from '@/stores/admin/userStore';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { Input } from '@/components/ui/input';
import { useOrderStore } from '@/stores/admin/orderStore';
import { Tryout } from '@/types/Tryout';

const userStore = useUserStore()
const orderStore = useOrderStore()

onMounted(() => {
    userStore.fetchUsersNotHasTryout()
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gift Tryout',
        href: 'admin/event',
    },
];

const props = withDefaults(
  defineProps<{
    tryout?: Partial<Tryout>
  }>(),
  {
    tryout: () => ({})
  }
);

</script>

<template>

    <Head title="Gift" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <PlusIcon @click="userStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" />
                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="userStore.handleSearch" v-model="userStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div class="flex gap-2 justify-center items-center">
                <p class="text-center">{{ props.tryout.title }}</p>
                <Link :href="`/admin/dashboard/has-order-tryout?tryout_code=${tryout.tryout_code}`" class="bg-gray-100 hover:bg-gray-200 py-2 px-4 cursor-pointer">{{ orderStore.orderCount ?? tryout.orders_count }}</Link>
            </div>
            <GiftTryoutTable :users-not-has-tryout="userStore.usersNotHasTryout" :current-page="userStore.paginationNotHasTryout?.current_page"
                :per-page="userStore.paginationNotHasTryout?.per_page" :user-store="userStore" :order-store="orderStore" :tryout="props.tryout" />
            <Pagination v-if="userStore.paginationNotHasTryout" :links="userStore.paginationNotHasTryout.links"
                @page-change="userStore.handlePageChangeHasNotTryout" />
        </div>
    </AppLayout>
</template>