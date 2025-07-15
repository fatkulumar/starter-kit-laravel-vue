<script lang="ts" setup>

interface Props {
  id: string
  label: string
  type?: string
  modelValue: string
  placeholder?: string
  required?: boolean
  disabled?: boolean
  error?: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()
</script>

<template>
  <div class="mb-4 w-full">
    <label
      :for="id"
      class="block mb-1 text-gray-700 dark:text-gray-200"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <input
      :id="id"
      :type="type || 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      class="block w-full px-3 py-2 border rounded-md focus:outline-none
             focus:ring-2 focus:ring-primary focus:border-primary
             disabled:opacity-60 disabled:cursor-not-allowed
             dark:bg-gray-800 dark:text-white dark:border-gray-600"
      :class="{ 'border-red-500 ring-red-500': error }"
    />

    <p v-if="error" class="text-sm text-red-500 mt-1">{{ error }}</p>
  </div>
</template>
