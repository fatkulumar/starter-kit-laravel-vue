import type { Order } from "@/types/Order";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { User } from "@/types";
import { Tryout } from "@/types/Tryout";
import { useUserStore } from "./userStore";

export type OrderListResponse = ApiResponse<PaginatedData<Order>>

export enum StatusOrder {
    PENDING = 'pending',
    PAID = 'paid',
    FAILED = 'failed',
    CANCELLED = 'cancelled',
}

interface FormOrder {
    user_id: string[];
    tryout_id: string;
    amount: number;
}

export const useOrderStore = defineStore('order-tryout-admin', {
    state: (): {
        orders: Order[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        orderCache: Map<string, OrderListResponse>,
        form: FormOrder,
        checkedAll: boolean,
        selectedUserIds: string[],
        orderCount: null | number
    } => ({
        orders: [] as Order[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        orderCache: new Map<string, OrderListResponse>(),
        form: {
            user_id: [],
            tryout_id: '',
            amount: 0,
        },
        checkedAll: false,
        selectedUserIds: [],
        orderCount: null,
    }),
    getters: {
        statusOrderOptions(): { label: string; value: StatusOrder }[] {
            return Object.values(StatusOrder).map((value) => ({
                label: value.charAt(0).toUpperCase() + value.slice(1),
                value,
            }))
        }
    },
    actions: {
        async fetchOrder(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_order_admin_${searchQuery}` : `page_order_admin_${page}`;

            try {
                if (this.orderCache.has(cacheKey)) {
                    const cached = this.orderCache.get(cacheKey)!;

                    if (cached && typeof cached === 'object' && 'data' in cached) {
                        const data = cached.data;

                        if (Array.isArray(data)) {
                            this.orders = data;
                            this.pagination = null;
                        } else {
                            this.orders = data.data;
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
                    ? `/api/dashboard/order?search=${encodeURIComponent(searchQuery)}`
                    : `/api/dashboard/order?page=${page}`;

                const response = await axios.get<OrderListResponse>(url);
                const orderData = response.data.data;

                if (Array.isArray(orderData)) {
                    this.orders = orderData;
                    this.pagination = null;
                } else {
                    this.orders = orderData.data;
                    this.pagination = {
                        current_page: orderData.current_page,
                        per_page: orderData.per_page,
                        total: orderData.total,
                        last_page: orderData.last_page,
                        next_page_url: orderData.next_page_url,
                        prev_page_url: orderData.prev_page_url,
                        from: orderData.from,
                        to: orderData.to,
                        path: orderData.path,
                        links: orderData.links,
                    };
                }

                this.page = page;

                this.orderCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data order' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleGiftTryout(): Promise<void> {
            const userStore = useUserStore();
            this.isLoading = true;
            this.error = null;
            const trueConfirm = confirm(`Gift ?`)
            if (trueConfirm) {
                const formData = new FormData();
                const url = `/api/dashboard/order/gift-tryout`;
                formData.append('_method', 'POST');
                this.form.user_id.forEach(id => {
                    formData.append('user_id[]', id);
                });
                formData.append('tryout_id', this.form.tryout_id);
                formData.append('amount', String(this.form.amount));
                formData.append('search', String(this.searchQuery));
                formData.append('page', String(this.page));

                try {
                    const response = await axios.post<ApiResponse<Order>>(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    if (response?.status === 200) {
                        await userStore.deleteCacheUsersNotHasTryout();
                        await userStore.fetchUsersNotHasTryout(userStore.pageNotHasTryout);
                        this.hanldeResetForm();
                        this.error = null;
                        this.orderCount = response.data.data.order_count;
                    }
                } catch (err: any) {
                    if (err?.response?.status === 422) {
                        this.error = err.response.data.errors || { message: 'Data tidak valid' };
                    } else {
                        this.error = err?.response?.data || { message: 'Gagal submit data order' };
                    }
                } finally {
                    this.isLoading = false;
                }
            }
        },

        hanldeResetForm(): void {
            this.form.user_id = [];
            this.form.amount = 0;
            this.form.tryout_id = '';

            this.checkedAll = false;
        },

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
    }
});