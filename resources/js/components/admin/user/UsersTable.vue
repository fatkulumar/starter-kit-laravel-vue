<script setup lang="ts">
import { User } from '@/types';
import { PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { useUserStore } from '@/stores/admin/userStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';

const { userStore, users } = defineProps({
    users: {
        type: Array as PropType<User[]>,
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
    userStore: {
        type: Object as PropType<ReturnType<typeof useUserStore>>,
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
                    <th scope="col" class="px-6 py-3">Foto</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                    <th scope="col" class="px-6 py-3 flex gap-2 items-center">
                        <Checkbox class="w-8 h-8" id="checkedAll" v-model="userStore.checkedAll"
                            @update:modelValue="(val) => userStore.toggleSelectAll(users)" />
                        <TrashIcon @click="userStore.hanldeConfirmDeleteAll" class="w-8 h-8 bg-red-400 rounded-md cursor-pointer p-1" v-if="userStore.selectedIds.length > 0" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in users" :key="index" class="border-b dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ index + 1 + (currentPage - 1) * perPage }}
                    </th>
                    <td class="px-6 py-4">
                        <img class="w-16" :src="item.profile?.photo_url">
                    </td>
                    <td class="px-6 py-4">{{ item.name }}</td>
                    <td class="px-6 py-4">{{ item.email }}</td>
                    <td class="px-6 py-4">{{ item.role }}</td>
                    <td class="px-6 py-4">
                        <a href="#" class="flex gap-2 font-medium text-blue-600 dark:text-blue-500 cursor-pointer">
                            <PencilIcon @click="userStore.handleEdit(item)" class="h-8 w-8 bg-red-600 rounded-md p-1" />
                            <TrashIcon @click="userStore.handleConfirmDelete(item)" class="h-8 w-8 bg-blue-600 rounded-md p-1" />
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <Input class="h-8 w-8 cursor-pointer" type="checkbox" :id="`checked-${item.id}`"
                            :checked="userStore.selectedIds.includes(item.id)"
                            @click="userStore.toggleSelectOne(item.id); userStore.syncCheckedAll(users)" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>