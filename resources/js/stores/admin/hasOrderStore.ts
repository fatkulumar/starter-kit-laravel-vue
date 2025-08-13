import type { Order } from "@/types/Order";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { User } from "@/types";
import { Tryout } from "@/types/Tryout";

export type OrderListResponse = ApiResponse<PaginatedData<Order>>

interface FormOrder {
    user_id: string[];
    tryout_id: string;
    amount: number;
}

export const useHasOrderStore = defineStore('has-order-tryout-admin', {
    state: (): {
        hasOrders: Order[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        form: FormOrder,
        checkedAll: boolean,
        selectedUserIds: string[],
        hasOrderCache: Map<string, OrderListResponse>,
        tryoutId: string,
        tryout_id: string
    } => ({
        hasOrders: [] as Order[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        form: {
            user_id: [],
            tryout_id: '',
            amount: 0,
        },
        checkedAll: false,
        selectedUserIds: [],
        hasOrderCache: new Map<string, OrderListResponse>(),
        tryoutId: '',
        tryout_id: ''
    }),
    actions: {
        async fetchHasOrder(tryoutId = '', page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;
            const isSearching = !!searchQuery;
            const cacheKey = isSearching
                ? `search_has_order_admin_${tryoutId}_${searchQuery}`
                : `page_has_order_admin_${page}_${tryoutId}`;

            try {
                this.tryoutId = tryoutId
                if (this.hasOrderCache.has(cacheKey)) {
                    const cached = this.hasOrderCache.get(cacheKey)!;
                    const data = cached.data;

                    if (Array.isArray(data)) {
                        this.hasOrders = data;
                        this.pagination = null;
                    } else {
                        this.hasOrders = data.data;
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

                const url = isSearching
                    ? `/api/dashboard/order/has-order?search=${encodeURIComponent(searchQuery)}&tryout_id=${tryoutId}`
                    : `/api/dashboard/order/has-order?page=${page}&tryout_id=${tryoutId}`;

                const response = await axios.get<OrderListResponse>(url);
                const resultData = response.data.data;

                if (Array.isArray(resultData)) {
                    this.hasOrders = resultData;
                    this.pagination = null;
                } else {
                    this.hasOrders = resultData.data;
                    this.pagination = {
                        current_page: resultData.current_page,
                        per_page: resultData.per_page,
                        total: resultData.total,
                        last_page: resultData.last_page,
                        next_page_url: resultData.next_page_url,
                        prev_page_url: resultData.prev_page_url,
                        from: resultData.from,
                        to: resultData.to,
                        path: resultData.path,
                        links: resultData.links,
                    };
                }

                this.page = page;
                this.hasOrderCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data yang sudah order' };
            } finally {
                this.isLoading = false;
            }
        },

        // async fetchTryouts(tryout_id = '', page = 1, search?: string): Promise<void> {
        //     this.isLoading = true;
        //     this.error = null;

        //     const searchQuery = search ?? this.searchQuery;
            
        //     const isSearching = !!searchQuery;
        //     const cacheKey = isSearching ? `search_tryout_admin_tryout_id_${tryout_id}_${searchQuery}` : `page_${page}_tryout_id_${tryout_id}`;
            
        //     try {
        //         this.tryout_id = this.tryout_id;
        //         if (this.hasOrderCache.has(cacheKey)) {
        //             const cached = this.hasOrderCache.get(cacheKey)!;

        //             if (cached && typeof cached === 'object' && 'data' in cached) {
        //                 const data = cached.data;

        //                 if (Array.isArray(data)) {
        //                     this.hasOrders = data;
        //                     this.pagination = null;
        //                 } else {
        //                     this.hasOrders = data.data;
        //                     this.pagination = {
        //                         current_page: data.current_page,
        //                         per_page: data.per_page,
        //                         total: data.total,
        //                         last_page: data.last_page,
        //                         next_page_url: data.next_page_url,
        //                         prev_page_url: data.prev_page_url,
        //                         from: data.from,
        //                         to: data.to,
        //                         path: data.path,
        //                         links: data.links,
        //                     };
        //                 }

        //                 this.page = page;
        //                 return;
        //             }
        //         }

        //         const url = isSearching
        //             ? `/api/dashboard/tryout?search=${encodeURIComponent(searchQuery)}&tryout_id=${tryout_id}`
        //             : `/api/dashboard/tryout?page=${page}&tryout_id=${tryout_id}`;

        //         const response = await axios.get<TryoutListResponse>(url);
        //         const tryoutData = response.data.data;

        //         if (Array.isArray(tryoutData)) {
        //             this.tryouts = tryoutData;
        //             this.pagination = null;
        //         } else {
        //             this.tryouts = tryoutData.data;
        //             this.pagination = {
        //                 current_page: tryoutData.current_page,
        //                 per_page: tryoutData.per_page,
        //                 total: tryoutData.total,
        //                 last_page: tryoutData.last_page,
        //                 next_page_url: tryoutData.next_page_url,
        //                 prev_page_url: tryoutData.prev_page_url,
        //                 from: tryoutData.from,
        //                 to: tryoutData.to,
        //                 path: tryoutData.path,
        //                 links: tryoutData.links,
        //             };
        //         }

        //         this.page = page;

        //         this.hasOrderCache.set(cacheKey, response.data);
        //     } catch (err: any) {
        //         this.error = err?.response?.data || { message: 'Gagal mengambil data tryout' };
        //     } finally {
        //         this.isLoading = false;
        //     }
        // },
        
        toggleSelectAll(users: User[], tryout: Partial<Tryout>): void {
            if (this.checkedAll) {
                const userIds = users.map(u => u.id);
                this.form.user_id = userIds;
                this.form.tryout_id = tryout.id ?? '';
                this.form.amount = tryout.price ?? 0;
            } else {
                this.form.user_id = [];
                this.form.tryout_id = '';
                this.form.amount = 0;
            }
        },


        toggleSelectOne(user: User, tryout: Partial<Tryout>): void {
            if (this.form.user_id.includes(user.id)) {
                this.form.user_id = this.form.user_id.filter(id => id !== user.id);
            } else {
                this.form.user_id.push(user.id);
            }

            this.checkedAll = false;
            this.form.tryout_id = tryout.id ?? '';
            this.form.amount = tryout.price ?? 0;
        },

        syncCheckedAll(users: User[]): void {
            this.checkedAll = users.length > 0 && users.every(user => this.form.user_id.includes(user.id));
        },

        handlePageChange(page: number): void {
            this.fetchHasOrder(this.tryoutId, page);
        },

        async handleSearch(): Promise<void> {
            await this.fetchHasOrder(this.tryoutId, this.page, this.searchQuery);
        },
    }
});