<script setup lang="ts">
import Button from "@/components/ui/button/Button.vue";
import { useTryoutStore } from "@/stores/student/tryoutStore";
import { Order } from "@/types/Order";
import { CheckCheck } from "lucide-vue-next";

export interface TryoutItem {
  id: number | string;
  title: string;
  start_time_formatted: string;
  end_time_formatted: string;
  price: number;
  orders: Order[];
}

type TryoutStoreType = ReturnType<typeof useTryoutStore>;

const props = defineProps<{
  tryouts: TryoutItem[];
  selectedIds: (number | string)[];
  amount: number;
  tryoutStore: TryoutStoreType;
}>();

const emits = defineEmits<{
  (e: "toggle-select", id: string): void;
  (e: "show-requirements"): void;
}>();

const onToggleSelect = (id: string) => emits("toggle-select", id);
const onShowRequirements = () => emits("show-requirements");
</script>

<template>
  <div>
    <h2 class="mb-5 truncate leading-tight font-semibold">Silahkan Pilih</h2>

    <div
      v-for="(item, index) in tryouts"
      :key="index"
      class="flex justify-between items-center border rounded-md p-4 mb-3 shadow-sm"
      :class="[ item.orders[0]?.user_id === $page.props.auth.user.id ? 'cursor-not-allowed' : 'border-card cursor-pointer' ]"
      @click="item.orders[0]?.user_id !== $page.props.auth.user.id && onToggleSelect(String(item.id))"
    >
      <div>
        <h3 class="font-bold text-lg">{{ item.title }}</h3>
        <p class="text-sm text-gray-700">
          {{ item.start_time_formatted }} - {{ item.end_time_formatted }}
        </p>
      </div>

      <CheckCheck v-if="selectedIds.includes(item.id)" class="text-card" />
    </div>

    <div class="w-full flex justify-between items-center" v-if="selectedIds.length > 0">
      <Button
        @click="onShowRequirements"
        class="cursor-pointer"
      >
        Kirim
      </Button>
      <p>{{ tryoutStore.formatRupiah(props.amount) }}</p>
    </div>
  </div>
</template>
