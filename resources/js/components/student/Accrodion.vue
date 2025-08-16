<script setup lang="ts">
import { Tryout } from "@/types/Tryout";
import { ref } from "vue";

// types/AccordionTryout.ts
export interface AccordionTryout {
  id: string | number;
  title: string;
  start_time_formatted: string;
  end_time_formatted: string;
}


const props = defineProps<{
    tryouts: AccordionTryout[];
}>();

const openItem = ref<string | number | null>(null);

function toggleAccordion(id: string | number) {
  openItem.value = openItem.value === id ? null : id;
}
</script>

<template>
    <div class="divide-y divide-slate-200">
        <div v-for="item in tryouts" :key="item.id" class="border-b border-slate-200">
            <button @click="toggleAccordion(item.id)"
                class="w-full flex justify-between items-center py-5 text-slate-800">
                <span>{{ item.title }}</span>
                <span class="text-slate-800 transition-transform duration-300"
                    :class="{ 'rotate-180': openItem === item.id }">
                    <svg v-if="openItem !== item.id" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                        fill="currentColor" class="w-4 h-4">
                        <path
                            d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="w-4 h-4">
                        <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                    </svg>
                </span>
            </button>
            <div class="overflow-hidden transition-all duration-300 ease-in-out"
                :style="{ maxHeight: openItem === item.id ? '200px' : '0' }">
                <div class="pb-5 text-sm text-slate-500">
                    📅 {{ item.start_time_formatted }} → {{ item.end_time_formatted }}
                </div>
            </div>
        </div>
    </div>
</template>
