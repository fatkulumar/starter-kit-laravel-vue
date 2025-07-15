<script lang="ts" setup>
interface Props {
    type: 'button' | 'submit' | 'reset',
    title: string
}

const props = defineProps<Props>()
</script>

<template>
    <button :type="type" class="bg-[var(--primary)] text-[var(--primary-foreground)] px-4 py-2 rounded">
        {{ title }}
    </button>
</template>