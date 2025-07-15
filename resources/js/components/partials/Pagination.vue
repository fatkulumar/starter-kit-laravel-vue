<script setup lang="ts">
import { PropType } from 'vue';

defineProps({
    links: {
        type: Array as PropType<Array<{
            url: string | null
            label: string
            active: boolean
        }>>,
        required: true
    }
})

const emit = defineEmits < {
        (e: 'page-change', page: number): void
    }> ()

function handleClick(url: string | null) {
    if (!url)  return
    const pageMatch = url?.match(/page=(\d+)/);
    const page = pageMatch ? parseInt(pageMatch[1]) : 1;
    emit('page-change', page)
}
</script>

<template>
    <div class="flex flex-wrap gap-1">
        <button v-for="link in links" :key="link.label" :disabled="!link.url" :class="[
            'px-3 py-1 border rounded',
            {
                'font-bold text-blue-500 border-blue-300': link.active,
                'text-gray-500 cursor-not-allowed': !link.url,
            }
        ]" @click="handleClick(link.url)" v-html="link.label" />
    </div>
</template>