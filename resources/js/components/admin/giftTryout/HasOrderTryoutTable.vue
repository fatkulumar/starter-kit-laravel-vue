<script setup lang="ts">
import { PropType } from 'vue';
import { useHasOrderStore } from '@/stores/admin/hasOrderStore';

const { hasOrders, hasOrderStore } = defineProps({
    hasOrders: {
        type: Array,
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
    hasOrderStore: {
        type: Object as PropType<ReturnType<typeof useHasOrderStore>>,
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
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in hasOrders" :key="index" class="border-b dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ index + 1 + (currentPage - 1) * perPage }}
                    </th>
                    <td class="px-6 py-4">{{ item.user.name }}</td>
                    <td class="px-6 py-4">{{ item.user.email }}</td>
                    <td class="px-6 py-4">{{ item.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>