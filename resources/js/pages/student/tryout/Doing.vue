<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue"
import QuestionAnswer from "@/components/student/QuestionAnswer.vue";
import { computed, onMounted, onUnmounted, ref } from "vue"

export interface QuestionSubtest {
  id: string;
  subtest_id: string;
  subject_id: string;
  option_a: string;
  option_b: string;
  option_c: string;
  option_d: string;
  option_e: string;
  correct_answer: string;
  explanation: string;
  created_at: string;
  updated_at: string;
}

export interface SubtestQuestion {
  id: string;
  title: string;
  amount_question: number;
  amount_minutes: number;
  questions: QuestionSubtest[];
  end_at: string;
  finish_at: string | null;
}

const props = defineProps<{ result: SubtestQuestion }>()

// simpan jawaban user
const answers = ref<Record<string, string | null>>(
  Object.fromEntries(props.result.questions.map(q => [q.id, null]))
);

// Countdown
const timeLeft = ref<number>(0) // detik tersisa
let interval: ReturnType<typeof setInterval>

const calculateTimeLeft = () => {
  const end = new Date(props.result.end_at).getTime()
  const now = new Date().getTime()
  const secondsLeft = Math.max(Math.floor((end - now) / 1000), 0)
  timeLeft.value = secondsLeft

  if (secondsLeft === 0) {
    clearInterval(interval)
    // TODO: trigger auto-submit atau disable jawaban
  }
}

onMounted(() => {
  props.result.questions.forEach(q => {
    if (!(q.id in answers.value)) {
      answers.value[q.id] = null;
    }
  });

  calculateTimeLeft()
  interval = setInterval(calculateTimeLeft, 1000)
});

onUnmounted(() => {
  clearInterval(interval)
})

// Format waktu hh:mm:ss
const formattedTime = computed(() => {
  const hours = Math.floor(timeLeft.value / 3600)
  const minutes = Math.floor((timeLeft.value % 3600) / 60)
  const seconds = timeLeft.value % 60
  return `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`
})

</script>

<template>
  <DefaultLayout>
    <div class="max-w-5xl mx-auto space-y-6">
       <p class="text-red-500 font-semibold text-lg mb-6">
      Waktu tersisa: {{ formattedTime }}
    </p>
      <h1 class="text-2xl font-bold mb-6">
        {{ props.result.title }} ({{ props.result.amount_question }} Soal, {{ props.result.amount_minutes }} Menit)
      </h1>

      <QuestionAnswer
        v-for="(q, i) in props.result.questions"
        :key="q.id"
        :question="q"
        :index="i"
        v-model="answers[q.id]"
      />

      <!-- Debug jawaban -->
      <pre class="mt-6 bg-slate-100 p-3 rounded-lg border text-xs">{{ answers }}</pre>
    </div>
  </DefaultLayout>
</template>
