<script setup lang="ts">
import { User } from '@/types';
import { PropType } from 'vue';
import { useUserStore } from '@/stores/admin/userStore';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import { Send } from 'lucide-vue-next';
import { useOrderStore } from '@/stores/admin/orderStore';
import { Tryout } from '@/types/Tryout';

const { usersNotHasTryout, orderStore } = defineProps({
    usersNotHasTryout: {
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
    },
    orderStore: {
        type: Object as PropType<ReturnType<typeof useOrderStore>>,
        required: true
    },
    tryout: {
        type: Object as PropType<Partial<Tryout>>,
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
                    <th scope="col" class="px-6 py-3 flex gap-2 items-center">
                        <Checkbox class="w-8 h-8" id="checkedAll" v-model="orderStore.checkedAll"
                            @update:modelValue="(val) => orderStore.toggleSelectAll(usersNotHasTryout, tryout)" />
                        <Send @click="orderStore.handleGiftTryout" class="w-8 h-8 bg-red-400 rounded-md cursor-pointer p-1" v-if="orderStore.form.user_id.length > 0" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in usersNotHasTryout" :key="index" class="border-b dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ index + 1 + (currentPage - 1) * perPage }}
                    </th>
                    <td class="px-6 py-4">{{ item.name }}</td>
                    <td class="px-6 py-4">{{ item.email }}</td>
                    <td class="px-6 py-4">
                        <Input class="h-8 w-8 cursor-pointer" type="checkbox" :id="`checked-${item.id}`"
                            :checked="orderStore.form.user_id.includes(item.id)"
                            @click="orderStore.toggleSelectOne(item, tryout); orderStore.syncCheckedAll(usersNotHasTryout)" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>