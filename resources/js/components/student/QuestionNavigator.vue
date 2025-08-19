<script setup lang="ts">
import { computed } from "vue"

const props = defineProps<{
  questions: { id: string }[]
  answers: Record<string, string | null>
  currentIndex: number
}>()

const emit = defineEmits<{
  (e: "navigate", index: number): void
}>()

// cek apakah soal sudah dijawab
const answeredStatus = computed(() =>
  props.questions.map((q) => props.answers[q.id] !== null)
)

function goTo(index: number) {
  emit("navigate", index)
}
</script>

<template>
  <div class="space-y-2">
    <h2 class="font-bold text-lg mb-4">Navigasi Soal</h2>
    <div class="grid grid-cols-5 gap-2">
      <button
        v-for="(q, i) in questions"
        :key="q.id"
        @click="goTo(i)"
        class="w-10 h-10 flex items-center justify-center rounded-full border
              transition font-semibold
              hover:bg-blue-100"
        :class="[
          currentIndex === i ? 'bg-blue-500 text-white' : '',
          answeredStatus[i] && currentIndex !== i ? 'bg-green-200' : '',
          !answeredStatus[i] && currentIndex !== i ? 'bg-gray-100' : ''
        ]"
      >
        {{ i + 1 }}
      </button>
    </div>
  </div>
</template>
