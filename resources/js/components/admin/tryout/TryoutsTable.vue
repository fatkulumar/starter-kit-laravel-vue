<script setup lang="ts">
import { Tryout } from '@/types/Tryout';
import { computed, PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { useTryoutStore } from '@/stores/admin/tryoutStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import { Send } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import QuestionMarkIcon from '@/components/partials/QuestionMarkIcon.vue';

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

const isActiveOptions = computed(() => tryoutStore.isActiveOptions);
const isLockedOptions = computed(() => tryoutStore.isLockedOptions);

</script>

<template>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Thumbnail</th>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Jenjang</th>
                    <th scope="col" class="px-6 py-3"></th>
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
                    <tr class="border-b dark:border-gray-700 border-gray-200 cursor-pointer hover:bg-gray-50"
                        :class="{ 'bg-white': tryoutStore.expandedIndex === index }">
                        <th @click="tryoutStore.toggleDetail(index)" scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white align-middle">
                            {{ index + 1 + (currentPage - 1) * perPage }}
                        </th>

                        <td @click="tryoutStore.toggleDetail(index)" class="px-6 py-4 align-middle">
                            <img v-if="item.thumbnail_url" class="w-16 object-cover rounded" :src="item.thumbnail_url"
                                alt="Thumbnail" />
                        </td>

                        <td @click="tryoutStore.toggleDetail(index)" class="px-6 py-4 align-middle">
                            {{ item.title }}
                        </td>

                        <td @click="tryoutStore.toggleDetail(index)" class="px-6 py-4 align-middle">
                            {{ item.grade?.name }}
                        </td>

                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-2">
                                <Link title="Gift Tryout" :href="`tryout/gift?tryout_code=${item.tryout_code}`">
                                <Send class="hover:bg-gray-200" />
                                </Link>
                                <Link title="Subtest" :href="`subtest?tryout_code=${item.tryout_code}`">
                                <QuestionMarkIcon class="h-6 w-6 hover:bg-gray-200" />
                                </Link>
                            </div>
                        </td>

                        <td class="px-6 py-4 align-middle">
                            <div class="flex gap-2 text-blue-600 dark:text-blue-500 cursor-pointer">
                                <PencilIcon @click="tryoutStore.handleEdit(item)"
                                    class="h-8 w-8 bg-red-600 rounded-md p-1" />
                                <TrashIcon @click="tryoutStore.handleConfirmDelete(item)"
                                    class="h-8 w-8 bg-blue-600 rounded-md p-1" />
                            </div>
                        </td>

                        <td class="px-6 py-4 align-middle">
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
                                        <td class="px-4 py-2 font-medium w-1/4">Harga</td>
                                        <td class="px-6 py-4">
                                            {{ item.price ? tryoutStore.formatRupiah(item.price) : 0 }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Mulai</td>
                                        <td class="px-4 py-2">{{ item.start_time_formatted }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Selesai</td>
                                        <td class="px-4 py-2">{{ item.end_time_formatted }}</td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Is Active</td>
                                        <td class="px-4 py-2">
                                            {{ tryoutStore.getLabelFromOptions(tryoutStore.isActiveOptions,
                                            item.is_active ? '1' : '0') }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="px-4 py-2 font-medium w-1/4">Is Locked</td>
                                        <td class="px-4 py-2">
                                            {{ tryoutStore.getLabelFromOptions(tryoutStore.isLockedOptions,
                                            item.is_locked ? '1' : '0') }}
                                        </td>
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