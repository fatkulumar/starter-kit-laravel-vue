<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Tryout } from '@/types/Tryout';
import { useTryoutStore } from '@/stores/student/tryoutStore';

const props = defineProps<{ tryout: Tryout }>();
const tryoutStore = useTryoutStore();

const countdown = ref("00:00:00:00");
const timeLeft = ref(0);

// daftar persyaratan
const requirements = ref([
  { id: 1, text: "Saya akan mengerjakan dengan jujur", checked: false },
  { id: 2, text: "Saya memahami aturan tryout", checked: false },
  // bisa tambah lagi
]);

// fungsi countdown
function startCountdown(endTime: string) {
  const end = new Date(endTime.replace(" ", "T")).getTime();

  const interval = window.setInterval(() => {
    const now = Date.now();
    let diff = Math.floor((end - now) / 1000);
    timeLeft.value = diff; // simpan angka mentah (bisa negatif)

    const days = Math.floor(diff / (3600 * 24));
    diff %= 3600 * 24;
    const hours = Math.floor(diff / 3600);
    diff %= 3600;
    const minutes = Math.floor(diff / 60);
    const seconds = diff % 60;

    countdown.value =
      String(days).padStart(2, "0") + ":" +
      String(hours).padStart(2, "0") + ":" +
      String(minutes).padStart(2, "0") + ":" +
      String(seconds).padStart(2, "0");
  }, 1000);

  onUnmounted(() => clearInterval(interval));
}

// tombol aktif kalau semua checkbox dicentang DAN countdown <= 0
const allChecked = computed(() => requirements.value.every(r => r.checked));
const canStart = computed(() => allChecked.value && timeLeft.value <= 0);
const showCountdown = computed(() => timeLeft.value > 0);

onMounted(() => {
  tryoutStore.fetchTryoutPurchasedByEventId(props.tryout.id);
  startCountdown(props.tryout.start_time);
});
</script>

<template>
  <DefaultLayout>
    <div class="p-4 space-y-4">
      <h1 class="text-lg font-bold">Doing Tryout</h1>

      <!-- Countdown -->
      <div class="flex items-center gap-2 text-orange-600 font-mono text-xl" v-show="showCountdown">
        ⏳ {{ countdown }}
      </div>

      <!-- Persyaratan -->
      <div class="space-y-2">
        <div v-for="req in requirements" :key="req.id" class="flex items-center gap-2">
          <input type="checkbox" v-model="req.checked" class="w-4 h-4" />
          <label>{{ req.text }}</label>
        </div>
      </div>

      <!-- Tombol mulai -->
      <button :disabled="!canStart" class="px-4 py-2 rounded text-white"
        :class="canStart ? 'bg-green-600 hover:bg-green-700 cursor-pointer' : 'bg-gray-400 cursor-not-allowed'"
        @click="tryoutStore.doingTryout(props.tryout.tryout_code)">
        Mulai Tryout
      </button>
    </div>
  </DefaultLayout>
</template>
