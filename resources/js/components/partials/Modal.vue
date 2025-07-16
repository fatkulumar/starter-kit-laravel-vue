<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur bg-opacity-50">
        <div class="dark:bg-gray-950 bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">
            <Button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" @click="$emit('close')">
                ✕
            </Button>
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import Button from '../ui/button/Button.vue';

defineProps<{
    show: boolean;
}>();
defineEmits(['close']);
</script>
