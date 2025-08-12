<script setup lang="ts">
import { CheckCheck } from "lucide-vue-next";

interface TryoutItem {
  id: number | string;
  title: string;
  start_time_formatted: string;
  end_time_formatted: string;
}

const props = defineProps<{
  tryouts: TryoutItem[];
  selectedIds: (number | string)[];
}>();

const emits = defineEmits<{
  (e: "toggle-select", id: number | string): void;
  (e: "show-requirements"): void;
}>();

const onToggleSelect = (id: number | string) => emits("toggle-select", id);
const onShowRequirements = () => emits("show-requirements");
</script>

<template>
  <div>
    <h2 class="mb-5 truncate leading-tight font-semibold">Silahkan Pilih</h2>

    <div
      v-for="(item, index) in tryouts"
      :key="index"
      class="flex justify-between items-center border border-card rounded-md p-4 mb-3 shadow-sm cursor-pointer"
      @click="onToggleSelect(item.id)"
    >
      <div>
        <h3 class="font-bold text-lg">{{ item.title }}</h3>
        <p class="text-sm text-gray-700">
          {{ item.start_time_formatted }} - {{ item.end_time_formatted }}
        </p>
      </div>

      <CheckCheck v-if="selectedIds.includes(item.id)" class="text-card" />
    </div>

    <div class="w-full">
      <Button
        v-if="selectedIds.length > 0"
        @click="onShowRequirements"
        class="mx-auto block cursor-pointer"
      >
        Kirim
      </Button>
    </div>
  </div>
</template>
