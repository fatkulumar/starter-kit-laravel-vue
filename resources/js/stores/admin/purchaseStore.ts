// import type { Purchase } from "@/types/Purchase";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { reactive } from "vue";
import { formatRupiah } from "@/utils/formatRupiah";
import { Order } from "@/types/Order";

export type PurchaseListResponse = ApiResponse<PaginatedData<Order>>

export enum OrderStatus {
    PENDING = 'pending',
    PAID = 'paid',
    FAILED = 'failed',
    CANCELLED = 'cancelled'
}

interface PurchaseForm {
    [key: string]: any;
    id: string;
    name: string | undefined;
    email: string | undefined;
    tryout_title: string | undefined;
    status: string;
    amount: number;
}


export const usePurchaseStore = defineStore('purchase-admin', {
    state: (): {
        purchases: Order[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        purchaseCache: Map<string, PurchaseListResponse>,
        showModal: boolean,
        previewProof: string,
        checkedAll: boolean,
        selectedIds: string[],
        expandedIndex: number | null,
        form: PurchaseForm
    } => ({
        purchases: [] as Order[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        purchaseCache: new Map<string, PurchaseListResponse>(),
        showModal: false,
        previewProof: '',
        checkedAll: false,
        selectedIds: [],
        expandedIndex: null,
        form: reactive<PurchaseForm>({
            id: '',
            name: '',
            email: '',
            tryout_title: '',
            status: '',
            amount: 0
        })
    }),
    getters: {
        isStatusOrderOptions(): { label: string; value: string }[] {
            return [
                { label: 'Pilih Status', value: '' },
                ...Object.entries(OrderStatus).map(([key, value]) => ({
                    label: key.charAt(0) + key.slice(1).toLowerCase(),
                    value: value as string,
                })),
            ];
        },
    },
    actions: {
        async fetchPurchases(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_purchase_admin_${searchQuery}` : `page_purchase_admin_${page}`;

            try {
                if (this.purchaseCache.has(cacheKey)) {
                    const cached = this.purchaseCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.purchases = cached.data;
                        this.pagination = null;
                    } else {
                        this.purchases = cached.data.data;
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
                    ? `/api/dashboard/purchase?search=${encodeURIComponent(searchQuery)}`
                    : `/api/dashboard/purchase?page=${page}`;

                const response = await axios.get<PurchaseListResponse>(url);
                const purchaseData = response.data.data;

                if (Array.isArray(purchaseData)) {
                    this.purchases = purchaseData;
                    this.pagination = null;
                } else {
                    this.purchases = purchaseData.data;
                    this.pagination = {
                        current_page: purchaseData.current_page,
                        per_page: purchaseData.per_page,
                        total: purchaseData.total,
                        last_page: purchaseData.last_page,
                        next_page_url: purchaseData.next_page_url,
                        prev_page_url: purchaseData.prev_page_url,
                        from: purchaseData.from,
                        to: purchaseData.to,
                        path: purchaseData.path,
                        links: purchaseData.links,
                    };
                }

                this.page = page;

                this.purchaseCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data event' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleSave(): Promise<void> {
            this.isLoading = true;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/api/dashboard/purchase/${this.form.id}`
                : `/api/dashboard/purchase`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('status', this.form.title);

            try {
                const response = await axios.post<ApiResponse<Order>>(url, formData);

                if (response?.status === 200) {
                    const updatedPurchase = response.data.data;

                    if (isEdit) {
                        this.purchases = this.purchases.map(u => u.id === updatedPurchase.id ? updatedPurchase : u);
                    } else {
                        this.purchases.unshift(updatedPurchase);
                    }

                    this.hanldeResetForm();
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data event' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        handleEdit(item: Order): void {
            this.form.id = item.id;
            this.form.name = item.user?.name;
            this.form.email = item.user?.email;
            this.form.tryout_title = item.tryout?.title;
            this.form.status = item.status;
            this.form.amount = item.amount;
            this.showModal = true;
        },

        async handleDelete(id: string) {
            this.isLoading = true;
            const url = `/api/dashboard/purchase/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.purchases = this.purchases.filter(purchase => purchase.id !== id);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data event' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async handleConfirmDelete(item: Order): Promise<void> {
            // const konfirm = confirm(`Hapus ${item.title}?`)
            // if (konfirm) {
            //     await this.handleDelete(item.id);
            // }
        },

        handlePageChange(page: number): void {
            this.fetchPurchases(page);
        },

        async handleSearch(): Promise<void> {
            await this.fetchPurchases(this.page, this.searchQuery);
        },

        hanldeResetForm(): void {
            this.form.id = '';
            this.form.status = '';
        },

        // handleFileChange(event: Event & { target: HTMLInputElement }): void {
        //     const target = event.target as HTMLInputElement;
        //     if (target.files && target.files[0]) {
        //         this.form.banner = target.files[0];
        //         const reader = new FileReader();
        //         reader.onload = () => {
        //             this.previewProof = reader.result as string;
        //         }
        //         reader.readAsDataURL(target.files[0]);
        //     }
        // },

        handleCloseModal(): void {
            this.showModal = false;
            this.hanldeResetForm();
        },

        toggleSelectAll(event: Order[]): void {
            if (this.checkedAll) {
                this.selectedIds = event.map(u => u.id);
            } else {
                this.selectedIds = [];
            }
        },

        toggleSelectOne(eventId: string): void {
            if (this.selectedIds.includes(eventId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== eventId);
            } else {
                this.selectedIds.push(eventId);
            }
            this.checkedAll = false;
        },

        syncCheckedAll(events: Order[]): void {
            this.checkedAll = events.length > 0 && events.every(event => this.selectedIds.includes(event.id));
        },

        async handleDeleteAll() {
            this.isLoading = true;
            const url = `/api/dashboard/purchase/delete-all `
            const form = {
                ids: this.selectedIds
            }
            try {
                const response = await axios.post<ApiResponse<Order>>(url, form);
                if (response?.status === 200) {
                    let deleteIds: string[] = [];
                    const data = response.data.data;

                    if (typeof data === 'string') {
                        deleteIds = [data];
                    } else if (Array.isArray(data)) {
                        deleteIds = data;
                    } else {
                        return;
                    }
                    this.purchases = this.purchases.filter(purchase => !deleteIds.includes(purchase.id));
                    this.error = null;
                    this.checkedAll = false;
                    this.selectedIds = [];
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data purchase' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async hanldeConfirmDeleteAll(): Promise<void> {
            const konfirm = confirm(`Hapus?`)
            if (konfirm) {
                await this.handleDeleteAll();
            }
        },

        toggleDetail(index: number): void {
            this.expandedIndex = this.expandedIndex === index ? null : index;
        },

        // convert to rupiah
        formatRupiah(value: number | string): string {
            return formatRupiah(value);
        }
    }
});