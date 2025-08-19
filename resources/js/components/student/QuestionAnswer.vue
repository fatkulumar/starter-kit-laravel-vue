<script setup lang="ts">
import { ref } from "vue"

export interface QuestionSubtest {
  id: string
  subtest_id: string
  subject_id: string
  option_a: string
  option_b: string
  option_c: string
  option_d: string
  option_e: string
  correct_answer: string
  explanation: string
  created_at: string
  updated_at: string
}

const props = defineProps<{
  question: QuestionSubtest
  index: number
  modelValue: string | null
}>()

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
}>()

function choose(value: string) {
  emit("update:modelValue", value)
}

</script>

<template>
  <div class="rounded-2xl shadow-md border border-slate-200 p-5 bg-white">
    <!-- Nomor + soal -->
    <p class="font-semibold mb-4">
      {{ index + 1 }}. {{ question.explanation }}
    </p>

    <!-- Grid 2 kolom opsi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <label class="block cursor-pointer" @click="choose('A')">
        <input
          class="peer hidden"
          type="radio"
          :name="'q-' + question.id"
          value="A"
          :checked="modelValue === 'A'"
        />
        <div
          class="rounded-xl border p-3 transition
                 hover:border-slate-400
                 peer-checked:border-blue-600 peer-checked:bg-blue-50"
        >
          <span class="font-semibold mr-2">A.</span> {{ question.option_a }}
        </div>
      </label>

      <label class="block cursor-pointer" @click="choose('B')">
        <input
          class="peer hidden"
          type="radio"
          :name="'q-' + question.id"
          value="B"
          :checked="modelValue === 'B'"
        />
        <div
          class="rounded-xl border p-3 transition
                 hover:border-slate-400
                 peer-checked:border-blue-600 peer-checked:bg-blue-50"
        >
          <span class="font-semibold mr-2">B.</span> {{ question.option_b }}
        </div>
      </label>

      <label class="block cursor-pointer" @click="choose('C')">
        <input
          class="peer hidden"
          type="radio"
          :name="'q-' + question.id"
          value="C"
          :checked="modelValue === 'C'"
        />
        <div
          class="rounded-xl border p-3 transition
                 hover:border-slate-400
                 peer-checked:border-blue-600 peer-checked:bg-blue-50"
        >
          <span class="font-semibold mr-2">C.</span> {{ question.option_c }}
        </div>
      </label>

      <label class="block cursor-pointer" @click="choose('D')">
        <input
          class="peer hidden"
          type="radio"
          :name="'q-' + question.id"
          value="D"
          :checked="modelValue === 'D'"
        />
        <div
          class="rounded-xl border p-3 transition
                 hover:border-slate-400
                 peer-checked:border-blue-600 peer-checked:bg-blue-50"
        >
          <span class="font-semibold mr-2">D.</span> {{ question.option_d }}
        </div>
      </label>

      <label class="block cursor-pointer sm:col-span-2" @click="choose('E')">
        <input
          class="peer hidden"
          type="radio"
          :name="'q-' + question.id"
          value="E"
          :checked="modelValue === 'E'"
        />
        <div
          class="rounded-xl border p-3 transition
                 hover:border-slate-400
                 peer-checked:border-blue-600 peer-checked:bg-blue-50"
        >
          <span class="font-semibold mr-2">E.</span> {{ question.option_e }}
        </div>
      </label>
    </div>
  </div>
</template>
