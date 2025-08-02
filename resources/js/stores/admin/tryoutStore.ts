import type { Tryout } from "@/types/Tryout";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { useForm } from "@inertiajs/vue3";
import { formatDatetimeLocal } from '@/utils/datetime'

export type TryoutListResponse = ApiResponse<PaginatedData<Tryout>>

interface TryoutForm {
    [key: string]: any;
    id: string;
    thumbnail?: File | null | undefined | string;
    start_time: string;
    end_time: string;
    event_id: string;
    title: string;
    description?: string;
    duration: number;
    is_active: string;
    is_locked: string;
    guide_link?: string;
    price: number;
}

export const useTryoutStore = defineStore('tryout-admin', {
    state: (): {
        tryouts: Tryout[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        tryoutCache: Map<string, TryoutListResponse>,
        showModal: boolean,
        form: TryoutForm,
        previewThumbnail: string,
        checkedAll: boolean,
        selectedIds: string[],
        expandedIndex: number | null,
        event_id: string
    } => ({
        tryouts: [] as Tryout[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        tryoutCache: new Map<string, TryoutListResponse>(),
        showModal: false,
        form: useForm<TryoutForm>({
            id: '',
            thumbnail: null,
            start_time: '',
            end_time: '',
            event_id: '',
            title: '',
            description: '',
            duration: 0,
            is_active: '',
            is_locked: '',
            guide_link: '',
            price: 0,
        }),
        previewThumbnail: '',
        checkedAll: false,
        selectedIds: [],
        expandedIndex: null,
        event_id: ''
    }),
    getters: {
        isActiveOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Pilih Status', value: '' },
                { label: 'Aktif', value: '1' },
                { label: 'Tidak Aktif', value: '0' }
            ];
        },

        isLockedOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Pilih Status', value: '' },
                { label: 'Terkunci', value: '1' },
                { label: 'Tidak Terkunci', value: '0' }
            ];
        }
    },
    actions: {
        async fetchTryouts(event_id = '', page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;
            
            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_tryout_event_id_${event_id}_${searchQuery}` : `page_${page}_event_id_${event_id}`;
            
            try {
                this.event_id = this.event_id;
                if (this.tryoutCache.has(cacheKey)) {
                    const cached = this.tryoutCache.get(cacheKey)!;

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
                    ? `/apiadmin/dashboard/tryout?search=${encodeURIComponent(searchQuery)}&event_id=${event_id}`
                    : `/apiadmin/dashboard/tryout?page=${page}&event_id=${event_id}`;

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

                this.tryoutCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data tryout' };
            } finally {
                this.isLoading = false;
            }
        },

        async handleSave(): Promise<void> {
            this.isLoading = true;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `/apiadmin/dashboard/tryout/${this.form.id}`
                : `/apiadmin/dashboard/tryout`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('event_id', this.form.event_id);
            formData.append('title', this.form.title);
            formData.append('description', this.form.description ?? '');
            formData.append('start_time', this.form.start_time);
            formData.append('end_time', this.form.end_time);
            formData.append('duration', String(this.form.duration));
            formData.append('is_active', this.form.is_active ?? '1');
            formData.append('is_locked', this.form.is_locked ?? '0');
            formData.append('guide_link', this.form.guide_link ?? '');
            formData.append('price', String(this.form.price));

            if (this.form.thumbnail) {
                formData.append('thumbnail', this.form.thumbnail);
            }

            try {
                const response = await axios.post<ApiResponse<Tryout>>(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response?.status === 200) {
                    const updatedEvent = response.data.data;

                    if (isEdit) {
                        this.tryouts = this.tryouts.map(u => u.id === updatedEvent.id ? updatedEvent : u);
                    } else {
                        this.tryouts.unshift(updatedEvent);
                    }

                    this.hanldeResetForm();
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal submit data tryout' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        handleEdit(item: Tryout): void {
            this.form.id = item.id;
            this.form.event_id = item.event_id;
            this.form.title = item.title;
            this.form.description = item.description ?? '';
            this.form.thumbnail = item.thumbnail ?? null;
            this.previewThumbnail = item.thumbnail_url ?? '';
            this.form.start_time = formatDatetimeLocal(new Date(item.start_time));
            this.form.end_time = formatDatetimeLocal(new Date(item.end_time));
            this.form.duration = item.duration;
            this.form.is_active = item.is_active ? '1' : '0';
            this.form.is_locked = item.is_locked ? '1' : '0';
            this.form.guide_link = item.guide_link ?? '';
            this.form.price = item.price ?? 0;
            this.showModal = true;
        },

        async handleDelete(id: string) {
            this.isLoading = true;
            const method = 'delete';
            const url = `/apiadmin/dashboard/tryout/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.tryouts = this.tryouts.filter(tryout => tryout.id !== id);
                    this.showModal = false;
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data tryout' };
                }
            } finally {
                this.isLoading = false;
            }
        },

        async handleConfirmDelete(item: Tryout): Promise<void> {
            const konfirm = confirm(`Hapus ${item.title}?`)
            if (konfirm) {
                await this.handleDelete(item.id);
            }
        },

        handlePageChange(page: number): void {
            this.fetchTryouts(this.event_id, page);
        },

        async handleSearch(eventId: string): Promise<void> {
            await this.fetchTryouts(eventId, this.page, this.searchQuery);
        },

        hanldeResetForm(): void {
            this.form.id = '';
            this.form.event_id = '';
            this.form.title = '';
            this.form.description = '';
            this.form.start_time = '';
            this.form.end_time = '';
            this.form.duration = 0;
            this.form.is_active = '0';
            this.form.is_locked = '';
            this.form.cover_image = '';
            this.form.guide_link = '';
            this.form.created_at = '';
            this.form.updated_at = '';
        },

        handleFileChange(event: Event & { target: HTMLInputElement }): void {
            const target = event.target as HTMLInputElement;
            if (target.files && target.files[0]) {
                this.form.thumbnail = target.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    this.previewThumbnail = reader.result as string;
                }
                reader.readAsDataURL(target.files[0]);
            }
        },

        handleCloseModal(): void {
            this.showModal = false;
            this.hanldeResetForm();
        },

        toggleSelectAll(tryout: Tryout[]): void {
            if (this.checkedAll) {
                this.selectedIds = tryout.map(u => u.id);
            } else {
                this.selectedIds = [];
            }
        },
        toggleSelectOne(tryoutId: string): void {
            if (this.selectedIds.includes(tryoutId)) {
                this.selectedIds = this.selectedIds.filter(id => id !== tryoutId);
            } else {
                this.selectedIds.push(tryoutId);
            }
            this.checkedAll = false;
        },

        syncCheckedAll(tryouts: Tryout[]): void {
            this.checkedAll = tryouts.length > 0 && tryouts.every(tryout => this.selectedIds.includes(tryout.id));
        },

        async handleDeleteAll() {
            this.isLoading = true;
            const method = 'post';
            const url = `/apiadmin/dashboard/apiadmin/dashboard/tryout/delete-all `
            const form = {
                ids: this.selectedIds
            }
            try {
                const response = await axios.post<ApiResponse<Tryout>>(url, form, {
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
                    this.tryouts = this.tryouts.filter(tryout => !deleteIds.includes(tryout.id));
                    this.error = null;
                }
            } catch (err: any) {
                if (err?.response?.status === 422) {
                    this.error = err.response.data.errors || { message: 'Data tidak valid' };
                } else {
                    this.error = err?.response?.data || { message: 'Gagal delete data tryout' };
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
    }
});