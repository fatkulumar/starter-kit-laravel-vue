import type { Tryout } from "@/types/Tryout";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { formatRupiah } from "@/utils/formatRupiah";
import { showSuccess, showError } from '@/utils/alert';
import { Purchase } from "@/types/Purchase";

export type TryoutListResponse = ApiResponse<PaginatedData<Tryout>>

export interface Task {
    label: string;
    file: File | null;
    preview: string;
}

export const useTryoutStore = defineStore('tryout-public', {
    state: (): {
        tryouts: Tryout[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        tryoutsCache: Map<string, TryoutListResponse>,
        prefixCacheKey: string,
        modalGetTryout: boolean,
        selectedIds: string[],
        modalUploadRequirements: boolean,
        // file upload requirement
        requirementFiles: Record<string, File | null>, // file tiap requirement
        requirementPreviews: Record<string, string>, // preview tiap requirement
        tasks: Task[],
        amount: number
    } => ({
        tryouts: [] as Tryout[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        tryoutsCache: new Map<string, TryoutListResponse>(),
        prefixCacheKey: '',
        modalGetTryout: false,
        selectedIds: [],
        modalUploadRequirements: false,
        requirementFiles: {},          // key = nama requirement, value = File/null
        requirementPreviews: {},
        tasks: [
            { label: 'Follow Instagram', file: null as File | null, preview: '' },
            // { label: 'Komen dan tag 10 Teman postingan Instagram', file: null as File | null, preview: '' },
            // { label: 'Share postingan ini di story kamu dan tag instagram', file: null as File | null, preview: '' },
            // { label: 'Share postingan instagram ke 3 grup belajar kamu', file: null as File | null, preview: '' }
        ],
        amount: 0
    }),
    actions: {
        // fetch tryout by event id
        async fetchTryoutByEventId(eventId = '', page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_tryout_by_event_id_public_${searchQuery}_${eventId}` : `public_tryout_by_event_id_page_all_${page}_${eventId}`;
            this.prefixCacheKey = cacheKey;

            try {
                if (this.tryoutsCache.has(cacheKey)) {
                    const cached = this.tryoutsCache.get(cacheKey)!;

                    if (cached && typeof cached === 'object' && 'data' in cached) {
                        const data = cached.data;

                        if (Array.isArray(data)) {
                            this.tryouts = data;
                            this.pagination = null;
                        } else {
                            this.tryouts = data.data;
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
                    ? `/api/public/tryout-by-event-id?search=${encodeURIComponent(searchQuery)}&event_id=${eventId}`
                    : `/api/public/tryout-by-event-id?page=${page}&event_id=${eventId}`;

                const response = await axios.get<TryoutListResponse>(url);
                const tryoutData = response.data.data;

                if (Array.isArray(tryoutData)) {
                    this.tryouts = tryoutData;
                    this.pagination = null;
                } else {
                    this.tryouts = tryoutData.data;
                    this.pagination = {
                        current_page: tryoutData.current_page,
                        per_page: tryoutData.per_page,
                        total: tryoutData.total,
                        last_page: tryoutData.last_page,
                        next_page_url: tryoutData.next_page_url,
                        prev_page_url: tryoutData.prev_page_url,
                        from: tryoutData.from,
                        to: tryoutData.to,
                        path: tryoutData.path,
                        links: tryoutData.links,
                    };
                }

                this.page = page;

                this.tryoutsCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data tryout' };
            } finally {
                this.isLoading = false;
            }
        },

        // delete cache
        async deleteCache(): Promise<void> {
            [...this.tryoutsCache.keys()]
                .filter(key =>
                    key.startsWith(this.prefixCacheKey)
                )
                .forEach(key => this.tryoutsCache.delete(key));
        },

        // show modal list tryout
        async handleShowModalGetTtryout(eventId: string): Promise<void> {
            this.fetchTryoutByEventId(eventId).then(() => this.modalGetTryout = true);
        },

        // close modalGetTryout
        async handleCloseModalGetTryout(): Promise<void> {
            this.modalGetTryout = false;
            this.selectedIds = [];
        },

        // select tryout
        toggleSelectOne(tryoutId: string): void {
            if (this.selectedIds.includes(tryoutId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== tryoutId);
            } else {
                this.selectedIds.push(tryoutId);
            }

            // Hitung ulang total harga berdasarkan selectedIds
            this.amount = this.tryouts
                .filter(t => this.selectedIds.includes(String(t.id)))
                .reduce((sum, t) => sum + t.price, 0);
        },

        // check id tryout in selectedIds
        checkIds(id: string): boolean {
            return this.selectedIds.includes(id);
        },

        // show modal upload requirement
        handleShowModalRequirement(): void {
            this.modalGetTryout = false;
            this.modalUploadRequirements = true;
        },

        // close modal upload requirement
        async handleCloseModalUploadRequirements(): Promise<void> {
            this.modalGetTryout = true;
            this.modalUploadRequirements = false;
            this.requirementFiles = {};          // key = nama requirement, value = File/null
            this.requirementPreviews = {};

            this.tasks = this.tasks.map(task => ({
                ...task,
                file: null,
                preview: ''
            }));
        },

        // reset form
        handleResetForm(): void {
            this.selectedIds = [];
        },

        /** ---------------------- REQUIREMENT FILE HANDLER ---------------------- **/
        setRequirementFile(key: string, file: File): void {
            this.requirementFiles[key] = file;
            this.requirementPreviews[key] = URL.createObjectURL(file);
        },
        getRequirementPreview(key: string): string {
            return this.requirementPreviews[key] || '';
        },
        hasRequirementFile(key: string): boolean {
            return !!this.requirementFiles[key];
        },

        /** ---------------------- UPLOAD REQUIREMENTS ---------------------- **/
        handleFileChange(e: Event, index: number) {
            const target = e.target as HTMLInputElement
            if (target.files && target.files[0]) {
                const file = target.files[0]
                this.tasks[index].file = file
                this.tasks[index].preview = URL.createObjectURL(file)
            }
        },

        async uploadRequirements() {
            this.isLoading = true;
            try {
                const formData = new FormData();

                // sertakan selectedIds
                this.selectedIds.forEach((id, idx) => {
                    formData.append(`tryout_id[${idx}]`, id);
                });

                formData.append('amount', String(this.amount));

                // sertakan label + file (jika ada)
                this.tasks.forEach((task, i) => {
                    formData.append(`tasks[${i}][label]`, task.label);
                    if (task.file) {
                        formData.append(`tasks[${i}][file]`, task.file);
                    }
                });

                // POST pakai form-data langsung
                const response = await axios.post<ApiResponse<Purchase>>('/api/student/purchase', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response?.status === 200) {
                    showSuccess(response.data.message);
                    this.error = null;
                    this.modalUploadRequirements = false;
                    this.modalGetTryout = false;
                }

            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                    const errors = err.response.data.errors || {}
                    showError(errors);
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data pembelian' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        // remove file selected
        removeFile(index: number) {
            this.tasks[index].file = null
            this.tasks[index].preview = ''
        },

        // conver to rupiah format'
        formatRupiah(value: number | string): string {
            return formatRupiah(value);
        }
    }
});