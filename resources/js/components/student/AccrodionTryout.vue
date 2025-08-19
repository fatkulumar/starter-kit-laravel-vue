<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

enum StatusTryoutAccordion {
  ACTIVE = "active",
  EXPIRED = "expired",
  UNAVAILABLE = "unavailable",
}
enum StatusQuestionAccordion {
  STAY = "stay",
  DOING = "doing",
  UNAVAILABLE = "done",
}

export interface AccordionTryout {
  id: string;
  title: string;
  tryout_code: string;
  tryout_id: string;
  status_tryout: StatusTryoutAccordion;
  status_doing: StatusQuestionAccordion;
  amount_question: number;
  amount_minutes: number;
  start_time: string;
  end_time: string;
  start_time_formatted: string;
  end_time_formatted: string;
  start_at: string;
}

const props = defineProps<{
  tryouts: AccordionTryout[];
}>();

const openItem = ref<string | number | null>(null);
function toggleAccordion(id: string | number) {
  openItem.value = openItem.value === id ? null : id;
}

// countdown state
const countdowns = ref<Record<string | number, string | null>>({});

function updateCountdown() {
  const now = new Date().getTime();
  props.tryouts.forEach((item) => {
    const end = new Date(item.start_time).getTime();
    const diff = end - now;

    if (diff <= 0) {
      countdowns.value[item.tryout_id] = null; // hilangkan countdown
    } else {
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((diff % (1000 * 60)) / 1000);

      // kalau semua nol → sembunyikan
      if (days === 0 && hours === 0 && minutes === 0 && seconds === 0) {
        countdowns.value[item.tryout_id] = null;
      } else {
        countdowns.value[item.tryout_id] =
          `${days}h ${hours}j ${minutes}m ${seconds}d`;
      }
    }
  });
}

let interval: number | undefined;

onMounted(() => {
  updateCountdown();
  interval = window.setInterval(updateCountdown, 1000);
});

onUnmounted(() => {
  if (interval) clearInterval(interval);
});
</script>

<template>
  <div class="divide-y divide-slate-200">
    <div
      v-for="item in tryouts"
      :key="item.tryout_id"
      class="border border-slate-200 rounded-lg mb-4 overflow-hidden shadow"
    >
      <!-- Header Accordion -->
      <button
        @click="toggleAccordion(item.tryout_id)"
        class="w-full flex justify-between items-center px-4 py-3 bg-slate-100 text-slate-800"
      >
        <span class="font-medium">{{ item.title }}</span>
        <span
          class="text-xs px-2 py-1 rounded-full"
          :class="item.status_tryout === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
        >
          {{ item.status_tryout }}
          {{ countdowns[item.tryout_id] }}
        </span>
        <span
          class="text-slate-800 transition-transform duration-300"
          :class="{ 'rotate-180': openItem === item.tryout_id }"
        >
          ⌄
        </span>
      </button>

      <!-- Body Accordion -->
      <div
        class="overflow-hidden transition-all duration-300 ease-in-out"
        :style="{ maxHeight: openItem === item.tryout_id ? '500px' : '0' }"
      >
        <div class="p-4 space-y-3 text-sm text-slate-700">
          <div>📅 {{ item.start_time_formatted }} → {{ item.end_time_formatted }}</div>
          <div class="flex items-center gap-2">
            ⏳ <span class="font-medium">Durasi:</span> {{ item.amount_minutes }} menit
          </div>
          <div class="flex items-center gap-2">
            📘 <span class="font-medium">Jumlah Soal:</span> {{ item.amount_question }} Soal
          </div>

          <!-- Countdown tampil hanya jika ada -->
          <div
            v-if="countdowns[item.tryout_id]"
            class="flex items-center gap-2 text-red-600 font-semibold"
          >
            🕒 <span>Countdown:</span> {{ countdowns[item.tryout_id] }}
          </div>

          <!-- Tombol Aksi -->
          <div class="flex gap-2 mt-4">
            <Link :href="`/student/tryout/start/${item.tryout_code}`" v-if="item.status_tryout == 'active'"
              class="flex-1 bg-orange-500 text-white font-medium rounded-md py-2 text-center"
            >
              Kerjakan 
            </Link>
             <a href="javascript:void(0)" v-else
              class="flex-1 bg-orange-300 text-white font-medium rounded-md py-2 text-center cursor-not-allowed"
            >
              Kerjakan 
            </a>
            <button
              class="flex-1 border border-slate-300 text-slate-400 font-medium rounded-md py-2"
            >
              Lihat Hasil Tryout
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
