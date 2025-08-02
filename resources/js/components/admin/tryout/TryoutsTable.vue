<script setup lang="ts">
import { Tryout } from '@/types/Tryout';
import { PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { useTryoutStore } from '@/stores/admin/tryoutStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';

const { tryoutStore, tryouts } = defineProps({
    tryouts: {
        type: Array as PropType<Tryout[]>,
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
    tryoutStore: {
        type: Object as PropType<ReturnType<typeof useTryoutStore>>,
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
                    <th scope="col" class="px-6 py-3">Thumbnail</th>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Is Active</th>
                    <th scope="col" class="px-6 py-3">Is Locked</th>
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3 flex gap-2 items-center">
                        <Checkbox class="w-8 h-8" id="checkedAll" v-model="tryoutStore.checkedAll"
                            @update:modelValue="(val) => tryoutStore.toggleSelectAll(tryouts)" />
                        <TrashIcon @click="tryoutStore.hanldeConfirmDeleteAll"
                            class="w-8 h-8 bg-red-400 rounded-md cursor-pointer p-1"
                            v-if="tryoutStore.selectedIds.length > 0" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in tryouts" :key="index">
                    <tr @click="tryoutStore.toggleDetail(index)" class="border-b dark:border-gray-700 border-gray-200 cursor-pointer hover:bg-gray-50" :class="{'bg-white': tryoutStore.expandedIndex === index}">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ index + 1 + (currentPage - 1) * perPage }}
                        </th>
                        <td class="px-6 py-4">
                            <img v-if="item.thumbnail_url" class="w-16" :src="item.thumbnail_url">
                        </td>
                        <td class="px-6 py-4">{{ item.title }}</td>
                        <td class="px-6 py-4">{{ item.is_active }}</td>
                        <td class="px-6 py-4">{{ item.is_locked }}</td>
                        <td class="px-6 py-4">{{ item.price ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="flex gap-2 font-medium text-blue-600 dark:text-blue-500 cursor-pointer">
                                <PencilIcon @click="tryoutStore.handleEdit(item)"
                                    class="h-8 w-8 bg-red-600 rounded-md p-1" />
                                <TrashIcon @click="tryoutStore.handleConfirmDelete(item)"
                                    class="h-8 w-8 bg-blue-600 rounded-md p-1" />
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <Input class="h-8 w-8 cursor-pointer" type="checkbox" :id="`checked-${item.id}`"
                                :checked="tryoutStore.selectedIds.includes(item.id)"
                                @click="tryoutStore.toggleSelectOne(item.id); tryoutStore.syncCheckedAll(tryouts)" />
                        </td>
                    </tr>

                    <tr v-if="tryoutStore.expandedIndex === index">
                        <td colspan="10" class="px-6 py-4 bg-gray-100 text-sm text-gray-700">
                            <table class="w-full table-auto border border-gray-300 rounded">
                                <tbody>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Mulai</td>
                                        <td class="px-4 py-2">{{ item.start_time_formatted }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Selesai</td>
                                        <td class="px-4 py-2">{{ item.end_time_formatted }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Durasi Pengerjaan</td>
                                        <td class="px-4 py-2">{{ item.duration }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Panduan</td>
                                        <td class="px-4 py-2">{{ item.guide_link }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Deskripsi</td>
                                        <td class="px-4 py-2">{{ item.description }}</td>
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