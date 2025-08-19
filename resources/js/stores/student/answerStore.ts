import type { Answer } from "@/types/Answer";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { reactive } from "vue";

export type AnswerListResponse = ApiResponse<PaginatedData<Answer>>

interface AnswerForm {
    [key: string]: any;
    id: string;
    subtest_id: string;
    question_id: string;
    answer: string;
}

export const useAnswerStore = defineStore('answer-student', {
    state: (): {
        answers: Answer[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        answerCache: Map<string, AnswerListResponse>,
        showModal: boolean,
        form: AnswerForm,
    } => ({
        answers: [] as Answer[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        answerCache: new Map<string, AnswerListResponse>(),
        showModal: false,
        form: reactive<AnswerForm>({
            id: '',
            user_id: '',
            question_id: '',
            answer: '',
            subtest_id: ''
        }),
    }),
    actions: {
        async fetchAnswers(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_answer_student_${searchQuery}` : `page_answer_student_${page}`;

            try {
                if (this.answerCache.has(cacheKey)) {
                    const cached = this.answerCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.answers = cached.data;
                        this.pagination = null;
                    } else {
                        this.answers = cached.data.data;
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
                    ? `/api/student/answer?search=${encodeURIComponent(searchQuery)}`
                    : `/api/student/answer?page=${page}`;

                const response = await axios.get<AnswerListResponse>(url);
                const answerData = response.data.data;

                if (Array.isArray(answerData)) {
                    this.answers = answerData;
                    this.pagination = null;
                } else {
                    this.answers = answerData.data;
                    this.pagination = {
                        current_page: answerData.current_page,
                        per_page: answerData.per_page,
                        total: answerData.total,
                        last_page: answerData.last_page,
                        next_page_url: answerData.next_page_url,
                        prev_page_url: answerData.prev_page_url,
                        from: answerData.from,
                        to: answerData.to,
                        path: answerData.path,
                        links: answerData.links,
                    };
                }

                this.page = page;

                this.answerCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data answer' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleSave(subtestId: string, answer: string | null, questionId: string): Promise<void> {
            this.isLoading = true;

            const url = `/api/student/answer`;

            const payload = {
                'subtest_id': subtestId,
                'question_id': questionId,
                'answer': answer
            }

            try {
                const response = await axios.post<ApiResponse<Answer>>(url, payload);

                if (response?.status === 200) {
                    const updatetAnswer = response.data.data;

                    this.answers = this.answers.map(u => u.id === updatetAnswer.id ? updatetAnswer : u);

                    this.hanldeResetForm();
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data answer' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        hanldeResetForm(): void
        {
            this.form.question_id = '';
            this.form.answer = '';
        }
    }
});