<script setup lang="ts">
import { ArrowRightCircle, ArrowLeftCircle } from 'lucide-vue-next';
import { PropType } from 'vue';


defineProps({
  links: {
    type: Array as PropType<
      Array<{
        url: string | null
        label: string
        active: boolean
      }>
    >,
    required: true,
  },
})

const emit = defineEmits<{
  (e: 'page-change', page: number): void
}>()

function handleClick(url: string | null) {
  if (!url) return
  const pageMatch = url?.match(/page=(\d+)/)
  const page = pageMatch ? parseInt(pageMatch[1]) : 1
  emit('page-change', page)
}

function isPrev(label: string) {
  return label === 'pagination.previous'
}
function isNext(label: string) {
  return label === 'pagination.next'
}
</script>

<template>
  <div class="flex flex-wrap gap-1 items-center">
    <button
      v-for="(link, index) in links"
      :key="index"
      :disabled="!link.url"
      :class="[
        'px-3 py-1 border rounded flex items-center justify-center gap-1',
        {
          'font-bold text-blue-500 border-blue-300': link.active,
          'text-gray-500 cursor-not-allowed': !link.url,
        },
      ]"
      @click="handleClick(link.url)"
    >
      <ArrowLeftCircle v-if="isPrev(link.label)" class="w-5 h-5" />
      <ArrowRightCircle v-else-if="isNext(link.label)" class="w-5 h-5" />
      <span v-else>{{ link.label }}</span>
    </button>
  </div>
</template>