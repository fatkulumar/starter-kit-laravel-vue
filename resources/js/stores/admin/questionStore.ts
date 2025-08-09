import type { Question } from "@/types/Question";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { useForm } from "@inertiajs/vue3";
import { showSuccess, showError } from '@/utils/alert'

export type QuestionListResponse = ApiResponse<PaginatedData<Question>>

interface QuestionForm {
    [key: string]: any;
    id: string;
    subtest_id: string | undefined;
    subject_id: string;
    option_a: string;
    option_b: string;
    option_c: string;
    option_d: string;
    option_e: string;
    explanation: string;
    correct_answer: string;
}

export const useQuestionStore = defineStore('question-admin', {
    state: (): {
        questions: Question[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        questionCache: Map<string, QuestionListResponse>,
        showModal: boolean,
        form: QuestionForm,
        checkedAll: boolean,
        questionNumber: number
    } => ({
        questions: [] as Question[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        questionCache: new Map<string, QuestionListResponse>(),
        showModal: false,
        form: useForm<QuestionForm>({
            id: '',
            subtest_id: '',
            subject_id: '',
            option_a: '',
            option_b: '',
            option_c: '',
            option_d: '',
            option_e: '',
            explanation: '',
            correct_answer: ''
        }),
        checkedAll: false,
        questionNumber: 1
    }),
    getters: {
        isCorrectAnswerOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Pilih Jawaban', value: '' },
                { label: 'A', value: 'a' },
                { label: 'B', value: 'b' },
                { label: 'C', value: 'c' },
                { label: 'D', value: 'd' },
                { label: 'E', value: 'e' },
            ];
        }
    },
    actions: {
        async fetchQuestions(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_question_admin_${searchQuery}_${this.form.subtest_id}` : `page_${page}_${this.form.subtest_id}`;

            try {
                if (this.questionCache.has(cacheKey)) {
                    const cached = this.questionCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.questions = cached.data;
                        this.pagination = null;
                    } else {
                        this.questions = cached.data.data;
                        this.pagination = {
                            current_page: cached.data.current_page,
                            per_page: cached.data.per_page,
                            total: cached.data.total,
                            last_page: cached.data.last_page,
                            next_page_url: cached.data.next_page_url,
                            prev_page_url: cached.data.prev_page_url,
                            from: cached.data.from,
                            to: cached.data.to,
                            path: cached.data.path,
                            links: cached.data.links,
                        };
                    }

                    this.page = page;
                    return;
                }

                const url = isSearching
                    ? `/apiadmin/dashboard/question?search=${encodeURIComponent(searchQuery)}&subtest_id=${this.form.subtest_id}`
                    : `/apiadmin/dashboard/question?page=${page}&subtest_id=${this.form.subtest_id}`;

                const response = await axios.get<QuestionListResponse>(url);
                const questionData = response.data.data;

                if (Array.isArray(questionData)) {
                    this.questions = questionData;
                    this.pagination = null;
                } else {
                    this.questions = questionData.data;
                    this.pagination = {
                        current_page: questionData.current_page,
                        per_page: questionData.per_page,
                        total: questionData.total,
                        last_page: questionData.last_page,
                        next_page_url: questionData.next_page_url,
                        prev_page_url: questionData.prev_page_url,
                        from: questionData.from,
                        to: questionData.to,
                        path: questionData.path,
                        links: questionData.links,
                    };
                }

                this.page = page;

                this.questionCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data question' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleSave(): Promise<void> {
            this.isLoading = true;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/apiadmin/dashboard/question/${this.form.id}`
                : `/apiadmin/dashboard/question`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('subtest_id', this.form.subtest_id ?? '');
            formData.append('subject_id', this.form.subject_id);
            formData.append('option_a', this.form.option_a);
            formData.append('option_b', this.form.option_b);
            formData.append('option_c', this.form.option_c);
            formData.append('option_d', this.form.option_d);
            formData.append('option_e', this.form.option_e);
            formData.append('correct_answer', this.form.correct_answer);
            formData.append('explanation', this.form.explanation);

            try {
                const response = await axios.post<ApiResponse<Question>>(url, formData);

                if (response?.status === 200) {
                    const updatedQuestion = response.data.data;

                    if (isEdit) {
                        this.questions = this.questions.map(u => u.id === updatedQuestion.id ? updatedQuestion : u);
                    } else {
                        this.questions.unshift(updatedQuestion);
                    }

                    showSuccess(response.data.message);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                    const errors = err.response.data.errors || {}
                    showError(errors);
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data question' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        handleEdit(item: Question): void {
            this.form.id = item.id;
            this.form.subtest_id = item.subtest_id;
            this.form.subject_id = item.subject_id;
            this.form.option_a = item.option_a;
            this.form.option_b = item.option_b;
            this.form.option_c = item.option_c;
            this.form.option_d = item.option_d;
            this.form.option_e = item.option_e;
            this.form.explanation = item.explanation;
            this.form.correct_answer = item.correct_answer;
            this.showModal = true;
        },

        async handleDelete(id: string) {
            this.isLoading = true;
            const url = `/apiadmin/dashboard/question/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.questions = this.questions.filter(question => question.id !== id);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data question' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        hanldeResetForm(): void {
            this.form.id = '';
            this.form.option_a = '';
            this.form.option_b = '';
            this.form.option_c = '';
            this.form.option_d = '';
            this.form.option_e = '';
            this.form.explanation = '';
            this.form.correct_answer = '';
        },

        getDataByNumber(number: number): void
        {
            this.questionNumber = number;
            if (this.questions[number - 1]) {
                this.form.id = this.questions[number - 1].id;
                this.form.subtest_id = this.questions[number - 1].subtest_id;
                this.form.option_a = this.questions[number - 1].option_a;
                this.form.option_b = this.questions[number - 1].option_b;
                this.form.option_c = this.questions[number - 1].option_c;
                this.form.option_d = this.questions[number - 1].option_d;
                this.form.option_e = this.questions[number - 1].option_e;
                this.form.explanation = this.questions[number - 1].explanation;
                this.form.correct_answer = this.questions[number - 1].correct_answer;
            } else {
                this.hanldeResetForm();
            }
        }
    }
});