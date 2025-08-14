<script setup lang="ts">
import { PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { usePurchaseStore } from '@/stores/admin/purchaseStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import { Link } from '@inertiajs/vue3';
import { Order } from '@/types/Order';

const { purchaseStore, purchases } = defineProps({
    purchases: {
        type: Array as PropType<Order[]>,
        required: true
    },
    currentPage: {
        type: Number,
        default: 1
    },
    perPage: {
        type: Number,
        default: 10
    },
    purchaseStore: {
        type: Object as PropType<ReturnType<typeof usePurchaseStore>>,
        required: true
    }
});

</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Tryout</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3 flex gap-2 items-center">
                        <Checkbox class="w-8 h-8" id="checkedAll" v-model="purchaseStore.checkedAll"
                            @update:modelValue="(val) => purchaseStore.toggleSelectAll(purchases)" />
                        <TrashIcon @click="purchaseStore.hanldeConfirmDeleteAll"
                            class="w-8 h-8 bg-red-400 rounded-md cursor-pointer p-1"
                            v-if="purchaseStore.selectedIds.length > 0" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in purchases" :key="index">
                    <tr
                        class="border-b dark:border-gray-700 border-gray-200 cursor-pointer hover:bg-gray-50"
                        :class="{ 'bg-white': purchaseStore.expandedIndex === index }">
                        <th @click="purchaseStore.toggleDetail(index)" scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ index + 1 + (currentPage - 1) * perPage }}
                        </th>
                        <td @click="purchaseStore.toggleDetail(index)" class="px-6 py-4">{{ item.user?.name }}</td>
                        <td @click="purchaseStore.toggleDetail(index)" class="px-6 py-4">{{ item.user?.email }}</td>
                        <td @click="purchaseStore.toggleDetail(index)" class="px-6 py-4">{{ item.tryout?.title }}</td>
                        <td @click="purchaseStore.toggleDetail(index)" class="px-6 py-4">{{ item.status }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="flex gap-2 font-medium text-blue-600 dark:text-blue-500 cursor-pointer">
                                <PencilIcon @click="purchaseStore.handleEdit(item)"
                                    class="h-8 w-8 bg-red-600 rounded-md p-1" />
                                <!-- <TrashIcon @click="purchaseStore.handleConfirmDelete(item)"
                                    class="h-8 w-8 bg-blue-600 rounded-md p-1" /> -->
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <Input class="h-8 w-8 cursor-pointer" type="checkbox" :id="`checked-${item.id}`"
                                :checked="purchaseStore.selectedIds.includes(item.id)"
                                @click="purchaseStore.toggleSelectOne(item.id); purchaseStore.syncCheckedAll(purchases)" />
                        </td>
                    </tr>

                    <tr v-if="purchaseStore.expandedIndex === index">
                        <td colspan="10" class="px-6 py-4 bg-gray-100 text-sm text-gray-700">
                            <table class="w-full table-auto border border-gray-300 rounded">
                                <tbody>
                                    <tr class="border-b" v-for="(itemPurchase, index) in item.purchases">
                                        <td class="px-4 py-2">
                                            <img :src="itemPurchase.proof_url" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>