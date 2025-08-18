<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue"
import QuestionAnswer from "@/components/student/QuestionAnswer.vue";
import { ref } from "vue"

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
}

const props = defineProps<{ result: SubtestQuestion }>()

// simpan jawaban user
const answers = ref<Record<string, string>>({})
</script>

<template>
  <DefaultLayout>
    <div class="max-w-5xl mx-auto space-y-6">
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
