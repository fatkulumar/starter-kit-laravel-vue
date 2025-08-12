import type { Tryout } from "@/types/Tryout";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { formatRupiah } from "@/utils/formatRupiah";
import { usePurchaseStore } from "./purchaseStore";
import { TryoutItem } from "@/components/admin/student/TryoutSelection.vue";

export type TryoutListResponse = ApiResponse<PaginatedData<TryoutItem>>

export interface Task {
    label: string;
    file: File | null;
    preview: string;
}

export const useTryoutStore = defineStore('tryout-public', {
    state: (): {
        tryouts: TryoutItem[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        tryoutsCache: Map<string, TryoutListResponse>,
        prefixCacheKey: string,
        modalGetTryout: boolean,
        selectedIds: string[],
        amount: number
    } => ({
        tryouts: [] as TryoutItem[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        tryoutsCache: new Map<string, TryoutListResponse>(),
        prefixCacheKey: '',
        modalGetTryout: false,
        selectedIds: [],
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
            const tryoutStore = useTryoutStore();
            this.fetchTryoutByEventId(eventId).then(() => tryoutStore.modalGetTryout = true);
        },

        // close modalGetTryout
        async handleCloseModalGetTryout(): Promise<void> {
            this.modalGetTryout = false;
            this.selectedIds = [];
            this.amount = 0;
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
            const purchaseStore = usePurchaseStore();
            this.modalGetTryout = false;
            purchaseStore.modalUploadRequirements = true;
        },

        // // convert to rupiah format'
        formatRupiah(value: number | string): string {
            return formatRupiah(value);
        }
    }
});