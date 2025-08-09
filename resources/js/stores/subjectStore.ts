import type { Subject } from "@/types/Subject";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";

export type SubjectListResponse = ApiResponse<PaginatedData<Subject>>

export const useSubjectStore = defineStore('subject-all', {
    state: (): {
        subjects: Subject[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        subjectCache: Map<string, SubjectListResponse>,
    } => ({
        subjects: [] as Subject[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        subjectCache: new Map<string, SubjectListResponse>(),
    }),
    getters: {
        subjectOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Optional', value: '' },
                ...state.subjects.map((subject: Subject) => ({
                    label: subject.name,
                    value: subject.id,
                })),
            ];
        }
    },
    actions: {
        async fetchSubject(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_subject_all_${searchQuery}` : `page_${page}`;

            try {
                if (this.subjectCache.has(cacheKey)) {
                    const cached = this.subjectCache.get(cacheKey)!;

                    if (cached && typeof cached === 'object' && 'data' in cached) {
                        const data = cached.data;

                        if (Array.isArray(data)) {
                            this.subjects = data;
                            this.pagination = null;
                        } else {
                            this.subjects = data.data;
                            this.pagination = {
                                current_page: data.current_page,
                                per_page: data.per_page,
                                total: data.total,
                                last_page: data.last_page,
                                next_page_url: data.next_page_url,
                                prev_page_url: data.prev_page_url,
                                from: data.from,
                                to: data.to,
                                path: data.path,
                                links: data.links,
                            };
                        }

                        this.page = page;
                        return;
                    }
                }

                const url = isSearching
                    ? `/api/dashboard/subject?search=${encodeURIComponent(searchQuery)}`
                    : `/api/dashboard/subject?page=${page}`;

                const response = await axios.get<SubjectListResponse>(url);
                const subjectData = response.data.data;

                if (Array.isArray(subjectData)) {
                    this.subjects = subjectData;
                    this.pagination = null;
                } else {
                    this.subjects = subjectData.data;
                    this.pagination = {
                        current_page: subjectData.current_page,
                        per_page: subjectData.per_page,
                        total: subjectData.total,
                        last_page: subjectData.last_page,
                        next_page_url: subjectData.next_page_url,
                        prev_page_url: subjectData.prev_page_url,
                        from: subjectData.from,
                        to: subjectData.to,
                        path: subjectData.path,
                        links: subjectData.links,
                    };
                }

                this.page = page;

                this.subjectCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data mata pelajaran' };
            } finally {
                this.isLoading = false;
            }
        },
    }
});