<script setup lang="ts">
import { User } from '@/types';
import { PropType } from 'vue';
import TrashIcon from '@/components/partials/TrashIcon.vue';
import PencilIcon from '@/components/partials/PencilIcon.vue';
import { useUserStore } from '@/stores/admin/userStore';

    defineProps({
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
    })
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
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in users" :key="index"
                class="border-b dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ index + 1 + (currentPage - 1) * perPage }}
                    </th>
                    <td class="px-6 py-4">
                        <img class="w-16" :src="item.profile?.photo_url" >
                    </td>
                    <td class="px-6 py-4">{{ item.name }}</td>
                    <td class="px-6 py-4">{{ item.email }}</td>
                    <td class="px-6 py-4">{{ item.role }}</td>
                    <td class="px-6 py-4">
                        <a href="#" class="flex  gap-2 font-medium text-blue-600 dark:text-blue-500 cursor-pointer">
                            <PencilIcon @click="userStore.handleEdit(item)" class="h-8 w-8" />
                            <TrashIcon @click="userStore.handleConfirmDelete(item)" class="h-8 w-8" />
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>