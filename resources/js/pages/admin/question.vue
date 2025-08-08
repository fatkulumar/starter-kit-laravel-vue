<script setup lang="ts">
import SubtestsTable from '@/components/admin/subtest/SubtestsTable.vue';
import Modal from '@/components/partials/Modal.vue';
import Pagination from '@/components/partials/Pagination.vue';
import PlusIcon from '@/components/partials/PlusIcon.vue';
import SearchIcon from '@/components/partials/SearchIcon.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useSubtestStore } from '@/stores/admin/subtestStore';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';
import { Tryout } from '@/types/Tryout';
import { Subtest } from '@/types/Subtest';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { useQuestionStore } from '@/stores/admin/questionStore';
import Select from '@/components/ui/select/select.vue';

const props = withDefaults(
    defineProps<{
        subtest?: Partial<Subtest>,
    }>(),
    {
        subtest: () => ({}),
    }
);

const questionStore = useQuestionStore()

onMounted(async () => {
    questionStore.form.subtest_id = props.subtest.id
    questionStore.fetchQuestions();
    questionStore.getDataByNumber(questionStore.questions.length);
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Question',
        href: 'question',
    },
];

</script>

<template>

    <Head title="Questions" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex flex-wrap gap-3">
                <Button v-for="value in props.subtest.amount_question" :key="value"
                    :disabled="value > questionStore.questions.length + 1" @click="questionStore.getDataByNumber(value)"
                    :class="[
                        'min-w-[40px] h-10 flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium shadow-sm ring-1 ring-gray-300 transition',

                        // 🔴 Jika nomor ini adalah yang sedang aktif (dipilih)
                        questionStore.questionNumber === value
                            ? 'bg-red-300 text-white hover:bg-red-400'

                            // ✅ Jika soal sudah dikerjakan / sudah ada
                            : value <= questionStore.questions.length
                                ? 'bg-green-300 text-white hover:bg-green-400'

                                // 🔵 Soal selanjutnya yang boleh diklik
                                : value === questionStore.questions.length + 1
                                    ? 'bg-blue-300 text-white hover:bg-blue-400 cursor-pointer'

                                    // ⚪ Soal setelahnya yang tidak boleh diklik
                                    : 'bg-white text-gray-700 hover:bg-gray-100 cursor-not-allowed'
                    ]">
                    {{ value }}
                </Button>

            </div>

            <form @submit.prevent="questionStore.handleSave">
                <div class="flex justify-between items-center mb-2">
                    <p>{{ props.subtest.title }}</p>
                    <Button class="cursor-pointer mt-2" type="submit">
                        <LoaderCircle v-if="questionStore.isLoading" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>

                <div class="flex gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white font-bold">
                        <Label for="option_a">A</Label>
                    </div>

                    <div class="flex flex-col w-full">
                        <Textarea id="option_a" autofocus tabindex="1" v-model="questionStore.form.option_a"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.option_a?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white font-bold">
                        <Label for="option_b">B</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Textarea id="option_b" autofocus tabindex="2" v-model="questionStore.form.option_b"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.option_b?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white font-bold">
                        <Label for="option_c">C</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Textarea id="option_c" autofocus tabindex="3" v-model="questionStore.form.option_c"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.option_c?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white font-bold">
                        <Label for="option_d">D</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Textarea id="option_d" autofocus tabindex="4" v-model="questionStore.form.option_d"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.option_d?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-green-500 text-white font-bold">
                        <Label for="option_e">E</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Textarea id="option_e" autofocus tabindex="5" v-model="questionStore.form.option_e"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.option_e?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div class="px-3 py-1 rounded-full bg-green-500 text-white text-sm font-semibold whitespace-nowrap">
                        <Label for="correct_option">Jawaban Benar</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Select id="is_online" v-model="questionStore.form.correct_answer" :tabindex="6"
                            :options="questionStore.isCorrectAnswerOptions" placeholder="Pilih Status" class="mt-2" />
                        <InputError :message="questionStore.error?.correct_option?.[0]" />
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-md bg-green-100 p-3 shadow-sm ring-1 ring-green-300">
                    <div class="px-3 py-1 rounded-full bg-green-500 text-white text-sm font-semibold whitespace-nowrap">
                        <Label for="explanation">Penjelasan</Label>
                    </div>
                    <div class="flex flex-col w-full">
                        <Textarea id="explanation" autofocus tabindex="7" v-model="questionStore.form.explanation"
                            class="w-full rounded-md border border-green-300 bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Masukkan jawaban..." />
                        <InputError :message="questionStore.error?.explanation?.[0]" />
                    </div>
                </div>

                <div class="flex justify-between items-center mt-2">
                    <p>{{ props.subtest.title }}</p>
                    <Button class="cursor-pointer" type="submit">Simpan</Button>
                </div>
            </form>
        </div>

        <!-- <Modal :show="subtestStore.showModal" @close="subtestStore.handleCloseModal" class="max-w-xl">
            <h2 class="mb-5 truncate leading-tight font-semibold">{{ subtestStore.form.id ? 'Form Edit Event' : 'Form Tambah Event' }}</h2>
            <form enctype="multipart/form-data" class="space-y-3" @submit.prevent="subtestStore.handleSave">

                <Label for="title">Judul</Label>
                <Input id="title" type="text" autofocus required :tabindex="2" autocomplete="title"
                    v-model="subtestStore.form.title" placeholder="Masukkan Judul" />
                <InputError :message="subtestStore.error?.title?.[0]" />

                <Label for="amount_question">Jumlah Pertanyaan</Label>
                <Input id="amount_question" type="number" required :tabindex="14" autocomplete="amount_question"
                    v-model="subtestStore.form.amount_question" />
                <InputError :message="subtestStore.error?.amount_question?.[0]" />

                <Label for="amount_minutes">Jumlah Menit</Label>
                <Input id="amount_minutes" type="number" required :tabindex="14" autocomplete="amount_minutes"
                    v-model="subtestStore.form.amount_minutes" />
                <InputError :message="subtestStore.error?.amount_minutes?.[0]" />

                <Button type="submit" class="mt-2 w-full" :tabindex="15" :disabled="subtestStore.isLoading">
                    <LoaderCircle v-if="subtestStore.isLoading" class="h-4 w-4 animate-spin" />
                    Simpan
                </Button>
            </form>
        </Modal> -->
    </AppLayout>
</template>