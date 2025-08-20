<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue"
import QuestionMultipleChoice from "@/components/student/QuestionMultipleChoice.vue"
import { computed, onMounted, onUnmounted, ref, watch } from "vue"
import QuestionNavigator from "@/components/student/QuestionNavigator.vue";
import { useAnswerStore } from "@/stores/student/answerStore";
import { showConfirm } from '@/utils/alert';

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
const finishExam = async () => {
  // cari soal yang belum diisi
  const unanswered = props.result.questions
    .filter(q => !answers.value[q.id] || answers.value[q.id]?.trim() === '')
    .map(q => q.id);

  if (unanswered.length > 0) {
    const confirmed = await showConfirm({
      title: 'Ada soal kosong!',
      text: `Masih ada ${unanswered.length} soal yang belum dijawab. Tetap kirim jawaban?`
    })

    if (!confirmed) return // batalkan submit
  }

  answerStore.finishExam(answers.value);
}

// Countdown
const timeLeft = ref<number>(0) // detik tersisa
let interval: ReturnType<typeof setInterval>

// hitung sisa waktu berdasarkan start_at + duration
const calculateTimeLeft = () => {
  const start = new Date(props.result.start_at).getTime()
  const end = start + props.result.duration * 60 * 1000 // duration dalam menit -> ms
  const now = new Date().getTime()
  const secondsLeft = Math.max(Math.floor((end - now) / 1000), 0)
  timeLeft.value = secondsLeft

  if (secondsLeft === 0) {
    clearInterval(interval);
    finishExam();
  }
}

onMounted(async () => {
  calculateTimeLeft();
  interval = setInterval(calculateTimeLeft, 1000);

  const q = props.result.questions[currentIndex.value]
  await answerStore.fetchAnswers(props.result.id);

  answers.value = Object.fromEntries(
    props.result.questions.map(q => {
      // cari jawaban dari backend
      const backendAnswer = answerStore.answers.find(a => a.question_id === q.id);
      return [q.id, backendAnswer ? backendAnswer.answer : null];
    })
  );
})

onUnmounted(() => {
  clearInterval(interval);
})

// Format waktu hh:mm:ss
const formattedTime = computed(() => {
  const hours = Math.floor(timeLeft.value / 3600)
  const minutes = Math.floor((timeLeft.value % 3600) / 60)
  const seconds = timeLeft.value % 60
  return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

const navigateTo = (index: number) => {
  currentIndex.value = index
}

const updateAnswer = (questionId: string, value: string | null) => {
  answers.value[questionId] = value
  answerStore.handleSave(value, questionId)
}

</script>

<template>
  <DefaultLayout>
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
      <!-- Navigasi soal -->
      <aside class="md:col-span-1">
        <QuestionNavigator :questions="props.result.questions" :answers="answers" :currentIndex="currentIndex"
          @navigate="navigateTo" />
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

        <QuestionMultipleChoice :question="props.result.questions[currentIndex]" :index="currentIndex"
          :model-value="answers[props.result.questions[currentIndex].id]"
          @update:model-value="val => updateAnswer(props.result.questions[currentIndex].id, val)" />

        <!-- navigasi bawah -->
        <div class="flex justify-between mt-6">
          <button class="px-4 py-2 bg-gray-200 rounded disabled:opacity-50" :disabled="currentIndex === 0"
            @click="prevQuestion">
            Kembali
          </button>

          <button v-if="currentIndex < props.result.questions.length - 1"
            class="px-4 py-2 bg-blue-600 text-white rounded" @click="nextQuestion">
            Selanjutnya
          </button>

          <button v-else class="px-4 py-2 bg-green-600 text-white rounded" @click="finishExam">
            Selesai
          </button>
        </div>
      </main>
    </div>
  </DefaultLayout>
</template>