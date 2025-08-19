<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue"
import QuestionAnswer from "@/components/student/QuestionAnswer.vue"
import { computed, onMounted, onUnmounted, ref, watch } from "vue"
import QuestionNavigator from "@/components/student/QuestionNavigator.vue";
import { useAnswerStore } from "@/stores/student/answerStore";
const answerStore = useAnswerStore();

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
  start_at: string;
  duration: number;
  finish_at: string | null;
}

const props = defineProps<{ result: SubtestQuestion }>()

// simpan jawaban user
const answers = ref<Record<string, string | null>>(
  Object.fromEntries(props.result.questions.map(q => [q.id, null]))
)

// index soal aktif
const currentIndex = ref(0)

// navigasi soal
const nextQuestion = () => {
  if (currentIndex.value < props.result.questions.length - 1) {
    currentIndex.value++
  }
}
const prevQuestion = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

// submit ujian
const finishExam = () => {
  console.log("Jawaban dikirim:", answers.value)
  alert("Jawaban kamu sudah terkirim!")
}

// Countdown
const timeLeft = ref<number>(0) // detik tersisa
let interval: ReturnType<typeof setInterval>

// hitung sisa waktu berdasarkan start_at + duration
const calculateTimeLeft = () => {
  const start = new Date(props.result.start_at).getTime()
  const end = start + props.result.duration * 60 * 1000 // duration dalam menit → ms
  const now = new Date().getTime()
  const secondsLeft = Math.max(Math.floor((end - now) / 1000), 0)
  timeLeft.value = secondsLeft

  if (secondsLeft === 0) {
    clearInterval(interval)
    finishExam()
  }
}

onMounted(() => {
  calculateTimeLeft()
  interval = setInterval(calculateTimeLeft, 1000)
})

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

const navigateTo = (index: number) => {
  currentIndex.value = index
}

watch(answers, (newVal, oldVal) => {
  const q = props.result.questions[currentIndex.value]
  if (q) {
    answerStore.handleSave(props.result.id, newVal[q.id], q.id)
  }
}, { deep: true })

</script>

<template>
  <DefaultLayout>
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
      <!-- Navigasi soal -->
      <aside class="md:col-span-1">
        <QuestionNavigator
          :questions="props.result.questions"
          :answers="answers"
          :currentIndex="currentIndex"
          @navigate="navigateTo"
        />
      </aside>

      <!-- Area soal -->
      <main class="md:col-span-3 space-y-6">
        <p class="text-red-500 font-semibold text-lg">
          Waktu tersisa: {{ formattedTime }}
        </p>

        <h1 class="text-2xl font-bold">
          {{ props.result.title }}
          ({{ props.result.amount_question }} Soal, {{ props.result.duration }} Menit)
        </h1>

        <QuestionAnswer
          v-if="props.result.questions[currentIndex]"
          :question="props.result.questions[currentIndex]"
          :index="currentIndex"
          v-model="answers[props.result.questions[currentIndex].id]"
        />

        <!-- <pre class="mt-6 bg-slate-100 p-3 rounded-lg border text-xs">{{ answers }}</pre> -->

        <!-- navigasi bawah -->
        <div class="flex justify-between mt-6">
          <button
            class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50"
            :disabled="currentIndex === 0"
            @click="prevQuestion"
          >
            Kembali
          </button>

          <button
            v-if="currentIndex < props.result.questions.length - 1"
            class="px-4 py-2 bg-blue-600 text-white rounded"
            @click="nextQuestion"
          >
            Selanjutnya
          </button>

          <button
            v-else
            class="px-4 py-2 bg-green-600 text-white rounded"
            @click="finishExam"
          >
            Selesai
          </button>
        </div>
      </main>
    </div>
  </DefaultLayout>
</template>