<script setup lang="ts">
import { Upload } from "lucide-vue-next";
import Button from "@/components/ui/button/Button.vue";

interface TaskItem {
  label: string;
  preview?: string | null;
}

const props = defineProps<{
  tasks: TaskItem[];
  isLoading: boolean;
}>();

const emits = defineEmits<{
  (e: "file-change", event: Event, index: number): void;
  (e: "remove-file", index: number): void;
  (e: "upload"): void;
}>();

const onFileChange = (e: Event, index: number) => emits("file-change", e, index);
const onRemoveFile = (index: number) => emits("remove-file", index);
const onUpload = () => emits("upload");
</script>

<template>
  <div>
    <h2 class="mb-5 truncate leading-tight font-semibold">
      Silahkan upload persyaratan
    </h2>

    <div class="grid grid-cols-2 gap-4">
      <div
        v-for="(task, index) in tasks"
        :key="index"
        class="relative w-full h-[160px]"
      >
        <label
          class="flex flex-col items-center text-center border border-blue-200 hover:shadow transition cursor-pointer rounded-lg w-full h-full overflow-hidden"
        >
          <input
            type="file"
            accept="image/*"
            class="hidden"
            @change="(e) => onFileChange(e, index)"
          />

          <template v-if="!task.preview">
            <div class="flex flex-col items-center justify-center w-full h-full p-4">
              <Upload class="w-10 h-10 text-blue-400" />
              <p class="text-sm font-medium mt-2">{{ task.label }}</p>
              <span class="text-blue-500 text-xs mt-2">Klik untuk upload</span>
            </div>
          </template>

          <template v-else>
            <div class="w-full h-full overflow-auto hide-scrollbar">
              <img
                :src="task.preview"
                class="object-contain min-w-full min-h-full"
              />
            </div>
          </template>
        </label>

        <button
          v-if="task.preview"
          type="button"
          @click="onRemoveFile(index)"
          class="absolute bottom-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded hover:bg-red-600"
        >
          Hapus
        </button>
      </div>
    </div>

    <div class="w-full mt-6">
      <Button
        class="mx-auto block cursor-pointer"
        :disabled="isLoading"
        @click="onUpload"
      >
        {{ isLoading ? 'Mengupload...' : 'Kirim' }}
      </Button>
    </div>
  </div>
</template>
