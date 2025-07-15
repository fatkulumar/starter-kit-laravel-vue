<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" @click="$emit('close')">
                ✕
            </button>
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    show: boolean;
}>();
defineEmits(['close']);
</script>
