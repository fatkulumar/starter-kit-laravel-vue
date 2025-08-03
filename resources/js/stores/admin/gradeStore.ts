import type { Grade } from "@/types/Grade";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";

export type GradeListResponse = ApiResponse<PaginatedData<Grade>>

export const useGradeStore = defineStore('grade-admin', {
    state: (): {
        grades: Grade[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        gradeCache: Map<string, GradeListResponse>,
    } => ({
        grades: [] as Grade[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        gradeCache: new Map<string, GradeListResponse>(),
    }),
    getters: {
        gradeOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Pilih Jenjang', value: '' },
                ...state.grades.map((grade: Grade) => ({
                    label: grade.name,
                    value: grade.id,
                })),
            ];
        }
    },
    actions: {
        async fetchGrade(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_grade_admin_${searchQuery}` : `page_${page}`;

            try {
                if (this.gradeCache.has(cacheKey)) {
                    const cached = this.gradeCache.get(cacheKey)!;

                    if (cached && typeof cached === 'object' && 'data' in cached) {
                        const data = cached.data;

                        if (Array.isArray(data)) {
                            this.grades = data;
                            this.pagination = null;
                        } else {
                            this.grades = data.data;
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
                    ? `/apiadmin/dashboard/grade?search=${encodeURIComponent(searchQuery)}`
                    : `/apiadmin/dashboard/grade?page=${page}`;

                const response = await axios.get<GradeListResponse>(url);
                const gradeData = response.data.data;

                if (Array.isArray(gradeData)) {
                    this.grades = gradeData;
                    this.pagination = null;
                } else {
                    this.grades = gradeData.data;
                    this.pagination = {
                        current_page: gradeData.current_page,
                        per_page: gradeData.per_page,
                        total: gradeData.total,
                        last_page: gradeData.last_page,
                        next_page_url: gradeData.next_page_url,
                        prev_page_url: gradeData.prev_page_url,
                        from: gradeData.from,
                        to: gradeData.to,
                        path: gradeData.path,
                        links: gradeData.links,
                    };
                }

                this.page = page;

                this.gradeCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data tryout' };
            } finally {
                this.isLoading = false;
            }
        },
    }
});