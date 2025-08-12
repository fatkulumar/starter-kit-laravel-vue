import type { Tryout } from "@/types/Tryout";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";

export type TryoutListResponse = ApiResponse<PaginatedData<Tryout>>

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
        tasks: { label: string, file: File | null, preview: string }[]
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
            { label: 'Komen dan tag 10 Teman postingan Instagram', file: null as File | null, preview: '' },
            { label: 'Share postingan ini di story kamu dan tag instagram', file: null as File | null, preview: '' },
            { label: 'Share postingan instagram ke 3 grup belajar kamu', file: null as File | null, preview: '' }
        ],
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
        },

        // select tryout
        toggleSelectOne(tryoutId: string): void {
            if (this.selectedIds.includes(tryoutId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== tryoutId);
            } else {
                this.selectedIds.push(tryoutId);
            }
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
            this.isLoading = true
            try {
                const formData = new FormData()
                this.tasks.forEach((task, i) => {
                    if (task.file) {
                        formData.append(`task_${i}`, task.file)
                    }
                })
                const response = await axios.post('/api/requirements', {
                    method: 'POST',
                    body: formData
                })
                this.modalUploadRequirements = false
            } catch (err) {
                console.error(err)
            } finally {
                this.isLoading = false
            }
        },

        removeFile(index: number) {
            this.tasks[index].file = null
            this.tasks[index].preview = ''
        }
    }
});