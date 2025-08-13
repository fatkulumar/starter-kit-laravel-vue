import type { Subtest } from "@/types/Subtest";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { reactive } from "vue";

export type SubtestListResponse = ApiResponse<PaginatedData<Subtest>>

interface SubtestForm {
    [key: string]: any;
    id: string;
    subject_id: string;
    title: string;
    tryout_id: string;
    amount_question: number;
    amount_minutes: number;
}

export const useSubtestStore = defineStore('subtest-admin', {
    state: (): {
        subtests: Subtest[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        subtestCache: Map<string, SubtestListResponse>,
        showModal: boolean,
        form: SubtestForm,
        checkedAll: boolean,
        selectedIds: string[],
    } => ({
        subtests: [] as Subtest[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        subtestCache: new Map<string, SubtestListResponse>(),
        showModal: false,
        form: reactive<SubtestForm>({
            id: '',
            subject_id: '',
            title: '',
            tryout_id: '',
            amount_question: 0,
            amount_minutes: 0,
        }),
        checkedAll: false,
        selectedIds: [],
    }),
    actions: {
        // Get data.
        async fetchSubtest(tryoutId = '', page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_subtest_admin_${searchQuery}_${tryoutId}` : `page_subtest_${page}_${tryoutId}`;

            try {
                this.form.tryout_id = tryoutId;
                if (this.subtestCache.has(cacheKey)) {
                    const cached = this.subtestCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.subtests = cached.data;
                        this.pagination = null;
                    } else {
                        this.subtests = cached.data.data;
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
                    ? `/api/dashboard/subtest?search=${encodeURIComponent(searchQuery)}&tryout_id=${tryoutId}`
                    : `/api/dashboard/subtest?page=${page}&tryout_id=${tryoutId}`;

                const response = await axios.get<SubtestListResponse>(url);
                const subteststData = response.data.data;

                if (Array.isArray(subteststData)) {
                    this.subtests = subteststData;
                    this.pagination = null;
                } else {
                    this.subtests = subteststData.data;
                    this.pagination = {
                        current_page: subteststData.current_page,
                        per_page: subteststData.per_page,
                        total: subteststData.total,
                        last_page: subteststData.last_page,
                        next_page_url: subteststData.next_page_url,
                        prev_page_url: subteststData.prev_page_url,
                        from: subteststData.from,
                        to: subteststData.to,
                        path: subteststData.path,
                        links: subteststData.links,
                    };
                }

                this.page = page;

                this.subtestCache.set(cacheKey, response.data);

            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data subtest' };
            } finally {
                this.isLoading = false;
            }
        },

        // Simpan data.
        async handleSave(): Promise<void> {
            this.isLoading = true;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/api/dashboard/subtest/${this.form.id}`
                : `/api/dashboard/subtest`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('title', this.form.title);
            formData.append('tryout_id', this.form.tryout_id);
            formData.append('subject_id', this.form.subject_id);
            formData.append('amount_question', String(this.form.amount_question));
            formData.append('amount_minutes', String(this.form.amount_minutes));
            
            try {
                const response = await axios.post<ApiResponse<Subtest>>(url, formData);
                
                if (response?.status === 200) {
                    const updatedSubtest = response.data.data;

                    if (isEdit) {
                        this.subtests = this.subtests.map(u => u.id === updatedSubtest.id ? updatedSubtest : u);
                    } else {
                        this.subtests.unshift(updatedSubtest);
                    }

                    this.hanldeResetForm();
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data subtest' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        // Edit data
        handleEdit(item: Subtest): void {
            this.form.id = item.id;
            this.form.title = item.title;
            this.form.tryout_id = item.tryout_id;
            this.form.amount_question = item.amount_question;
            this.form.amount_minutes = item.amount_minutes;
            this.form.subject_id = item.subject_id;
            this.showModal = true;
        },

        // delete data
        async handleDelete(id: string) {
            this.isLoading = true;
            const method = 'delete';
            const url = `/api/dashboard/subtest/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.subtests = this.subtests.filter(subtest => subtest.id !== id);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data subtest' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        // confirm before delete data
        async handleConfirmDelete(item: Subtest): Promise<void> {
            const konfirm = confirm(`Hapus ${item.title}?`)
            if (konfirm) {
                await this.handleDelete(item.id);
            }
        },

        // Pagination.
        handlePageChange(page: number): void {
            this.fetchSubtest(this.form.tryout_id, page);
        },

        // Searching
        async handleSearch(): Promise<void> {
            await this.fetchSubtest(this.form.tryout_id, this.page, this.searchQuery);
        },

        // Reset form.
        hanldeResetForm(): void {
            this.form.id = '';
            this.form.title = '';
            this.form.subject_id = '';
            this.form.amount_question = 0;
            this.form.amount_minutes = 0;
        },

        // Close Modal.
        handleCloseModal(): void {
            this.showModal = false;
            this.hanldeResetForm();
        },

        // Checkbox select all.
        toggleSelectAll(subtest: Subtest[]): void {
            if (this.checkedAll) {
                this.selectedIds = subtest.map(u => u.id);
            } else {
                this.selectedIds = [];
            }
        },

        // Select one.
        toggleSelectOne(subtestId: string): void {
            if (this.selectedIds.includes(subtestId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== subtestId);
            } else {
                this.selectedIds.push(subtestId);
            }
            this.checkedAll = false;
        },

        // check id in ids.
        syncCheckedAll(subtest: Subtest[]): void {
            this.checkedAll = subtest.length > 0 && subtest.every(subtest => this.selectedIds.includes(subtest.id));
        },

        // Delete all.
        async handleDeleteAll() {
            this.isLoading = true;
            const url = `/api/dashboard/subtest/delete-all `
            const form = {
                ids: this.selectedIds
            }
            try {
                const response = await axios.post<ApiResponse<Event>>(url, form, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
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
                    this.subtests = this.subtests.filter(subtest => !deleteIds.includes(subtest.id));
                    this.error = null;
                    this.checkedAll = false;
                    this.selectedIds = [];
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data subtest' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        // Confirm before delete all.
        async hanldeConfirmDeleteAll(): Promise<void> {
            const konfirm = confirm(`Hapus?`)
            if (konfirm) {
                await this.handleDeleteAll();
            }
        },
    }
});