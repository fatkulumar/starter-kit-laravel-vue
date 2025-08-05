<script setup lang="ts">
import HasOrderTryoutTable from '@/components/admin/giftTryout/HasOrderTryoutTable.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
// import { useUserStore } from '@/stores/admin/userStore';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { Input } from '@/components/ui/input';
// import { useOrderStore } from '@/stores/admin/orderStore';
import { Tryout } from '@/types/Tryout';
import { useHasOrderStore } from '@/stores/admin/hasOrderStore';

// const userStore = useUserStore()
// const orderStore = useOrderStore()
const hasOrderStore = useHasOrderStore();

onMounted(() => {
    hasOrderStore.fetchHasOrder(props.tryout.id)
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sudah Order Tryout',
        href: 'admin/event',
    },
];

const props = withDefaults(
  defineProps<{
    tryout?: Partial<Tryout>,
  }>(),
  {
    tryout: () => ({}),
  }
);

</script>

<template>

    <Head title="Sudah Order Tryout" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex items-center justify-between my-4">
                <!-- <PlusIcon @click="hasOrderStore.showModal = true"
                    class="h-8 w-8 bg-green-600 rounded-md p-1 cursor-pointer" /> -->
                <div class="relative flex items-center">
                    <SearchIcon class="w-8 h-8" />
                    <Input :tabIndex="2" @input="hasOrderStore.handleSearch" v-model="hasOrderStore.searchQuery" type="text"
                        placeholder="Cari..."
                        class="w-full pl-16 pr-4 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div class="flex gap-2 justify-center items-center">
                <p class="text-center">{{ props.tryout.title }}</p>
            </div>
            <HasOrderTryoutTable :has-orders="hasOrderStore.hasOrders" :current-page="hasOrderStore.pagination?.current_page"
                :per-page="hasOrderStore.pagination?.per_page" :user-store="hasOrderStore" :has-order-store="hasOrderStore" :tryout="props.tryout" />
            <Pagination v-if="hasOrderStore.pagination" :links="hasOrderStore.pagination.links"
                @page-change="hasOrderStore.handlePageChange" />
        </div>
    </AppLayout>
</template>