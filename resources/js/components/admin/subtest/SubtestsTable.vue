<script setup lang="ts">
import { Subtest } from '@/types/Subtest';
import { PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { useSubtestStore } from '@/stores/admin/subtestStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';

const { subtestStore, subtests } = defineProps({
    subtests: {
        type: Array as PropType<Subtest[]>,
        required: false,
        default: () => []
    },
    currentPage: {
        type: Number,
        default: 1
    },
    perPage: {
        type: Number,
        default: 10
    },
    subtestStore: {
        type: Object as PropType<ReturnType<typeof useSubtestStore>>,
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
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Jumlah Pertanyaan</th>
                    <th scope="col" class="px-6 py-3">Jumlah Menit</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3 flex gap-2 items-center">
                        <Checkbox class="w-8 h-8" id="checkedAll" v-model="subtestStore.checkedAll"
                            @update:modelValue="(val) => subtestStore.toggleSelectAll(subtests)" />
                        <TrashIcon @click="subtestStore.hanldeConfirmDeleteAll"
                            class="w-8 h-8 bg-red-400 rounded-md cursor-pointer p-1"
                            v-if="subtestStore.selectedIds.length > 0" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in subtests" :key="index">
                    <tr class="border-b dark:border-gray-700 border-gray-200 cursor-pointer hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ index + 1 + (currentPage - 1) * perPage }}
                        </th>
                        <td class="px-6 py-4">{{ item.title }}</td>
                        <td class="px-6 py-4">{{ item.amount_question }}</td>
                        <td class="px-6 py-4">{{ item.amount_minutes }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="flex gap-2 font-medium text-blue-600 dark:text-blue-500 cursor-pointer">
                                <PencilIcon @click="subtestStore.handleEdit(item)"
                                    class="h-8 w-8 bg-red-600 rounded-md p-1" />
                                <TrashIcon @click="subtestStore.handleConfirmDelete(item)"
                                    class="h-8 w-8 bg-blue-600 rounded-md p-1" />
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <Input class="h-8 w-8 cursor-pointer" type="checkbox" :id="`checked-${item.id}`"
                                :checked="subtestStore.selectedIds.includes(item.id)"
                                @click="subtestStore.toggleSelectOne(item.id); subtestStore.syncCheckedAll(subtests)" />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>