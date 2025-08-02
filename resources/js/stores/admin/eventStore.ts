import type { Event } from "@/types/Event";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { useForm } from "@inertiajs/vue3";
import { formatDatetimeLocal } from '@/utils/datetime'

export type EventListResponse = ApiResponse<PaginatedData<Event>>

interface EventForm {
    [key: string]: any;
    id: string;
    title: string;
    description?: string;
    banner?: File | null | undefined | string;
    start_time: string; 
    end_time: string;
    registration_deadline: string;
    preliminary_date: string;
    final_date: string;
    whatsapp_group_link?: string;
    guidebook_link?: string;
    location?: string;
    is_online: string;
    link_zoom?: string;
    quota: number;
}

export const useEventStore = defineStore('event-admin', {
    state: (): {
        events: Event[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        eventCache: Map<string, EventListResponse>,
        showModal: boolean,
        form: EventForm,
        previewBanner: string,
        checkedAll: boolean,
        selectedIds: string[],
        expandedIndex: number | null
    } => ({
        events: [] as Event[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        eventCache: new Map<string, EventListResponse>(),
        showModal: false,
        form: useForm<EventForm>({
            id: '',
            title: '',
            description: '',
            banner: '',
            start_time: '', 
            end_time: '',
            registration_deadline: '',
            preliminary_date: '',
            final_date: '',
            whatsapp_group_link: '',
            guidebook_link: '',
            location: '',
            is_online: '0',
            link_zoom: '',
            quota: 0
        }),
        previewBanner: '',
        checkedAll: false,
        selectedIds: [],
        expandedIndex: null
    }),
    getters: {
        isOnlineOptions(state): { label: string; value: string | number }[] {
            return [
                { label: 'Pilih Status', value: '' }, 
                { label: 'Online', value: '1' },
                { label: 'Tidak Online', value: '0' }
            ];
        }
    },
    actions: {
        async fetchEvents(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_event_admin_${searchQuery}` : `page_${page}`;

            try {
                if (this.eventCache.has(cacheKey)) {
                    const cached = this.eventCache.get(cacheKey)!;

                    if (Array.isArray(cached.data)) {
                        this.events = cached.data;
                        this.pagination = null;
                    } else {
                        this.events = cached.data.data;
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
                    ? `/apiadmin/dashboard/event?search=${encodeURIComponent(searchQuery)}`
                    : `/apiadmin/dashboard/event?page=${page}`;

                const response = await axios.get<EventListResponse>(url);
                const eventData = response.data.data;

                if (Array.isArray(eventData)) {
                    this.events = eventData;
                    this.pagination = null;
                } else {
                    this.events = eventData.data;
                    this.pagination = {
                        current_page: eventData.current_page,
                        per_page: eventData.per_page,
                        total: eventData.total,
                        last_page: eventData.last_page,
                        next_page_url: eventData.next_page_url,
                        prev_page_url: eventData.prev_page_url,
                        from: eventData.from,
                        to: eventData.to,
                        path: eventData.path,
                        links: eventData.links,
                    };
                }

                this.page = page;

                this.eventCache.set(cacheKey, response.data);
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
                ? `/apiadmin/dashboard/event/${this.form.id}`
                : `/apiadmin/dashboard/event`;

            const formData = new FormData();
            if (isEdit) {
                formData.append('_method', 'PUT');
                formData.append('id', this.form.id);
            }

            formData.append('title', this.form.title);
            formData.append('description', this.form.description ?? '');
            formData.append('start_time', this.form.start_time);
            formData.append('end_time', this.form.end_time);
            formData.append('end_time', this.form.end_time);
            formData.append('final_date', this.form.final_date);
            formData.append('registration_deadline', this.form.registration_deadline);
            formData.append('preliminary_date', this.form.preliminary_date);
            formData.append('whatsapp_group_link', this.form.whatsapp_group_link ?? '');
            formData.append('guidebook_link', this.form.guidebook_link ?? '');
            formData.append('location', this.form.location ?? '');
            formData.append('is_online', this.form.is_online ? '1' : '0');
            formData.append('link_zoom', this.form.link_zoom ?? '');
            formData.append('quota', String(this.form.quota) ?? 0);

            if (this.form.banner) {
                formData.append('banner', this.form.banner);
            }

            try {
                const response = await axios.post<ApiResponse<Event>>(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response?.status === 200) {
                    const updatedEvent = response.data.data;

                    if (isEdit) {
                        this.events = this.events.map(u => u.id === updatedEvent.id ? updatedEvent : u);
                    } else {
                        this.events.unshift(updatedEvent);
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

        handleEdit(item: Event): void {
            this.form.id = item.id;
            this.form.title = item.title;
            this.form.description = item.description ?? '';
            this.form.banner = item.banner;
            this.previewBanner = item.banner_url;
            this.form.start_time = formatDatetimeLocal(new Date(item.start_time));
            this.form.end_time = formatDatetimeLocal(new Date(item.end_time));
            this.form.registration_deadline = formatDatetimeLocal(new Date(item.registration_deadline));
            this.form.preliminary_date = formatDatetimeLocal(new Date(item.preliminary_date));
            this.form.final_date = formatDatetimeLocal(new Date(item.final_date));
            this.form.whatsapp_group_link = item.whatsapp_group_link;
            this.form.guidebook_link = item.guidebook_link;
            this.form.location = item.location;
            this.form.is_online = item.is_online ? '1' : '0';
            this.form.link_zoom = item.link_zoom;
            this.form.quota = item.quota;
            this.showModal = true;
        },

        async handleDelete(id: string) {
            this.isLoading = true;
            const method = 'delete';
            const url = `/apiadmin/dashboard/event/${id}`
            try {
                const response = await axios.delete<ApiResponse<string>>(url);
                if (response?.status === 200) {
                    this.events = this.events.filter(event => event.id !== id);
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

        async handleConfirmDelete(item: Event): Promise<void> {
            const konfirm = confirm(`Hapus ${item.title}?`)
            if (konfirm) {
                await this.handleDelete(item.id);
            }
        },

        handlePageChange(page: number): void {
            this.fetchEvents(page);
        },

        async handleSearch(): Promise<void> {
            await this.fetchEvents(this.page, this.searchQuery);
        },

        hanldeResetForm(): void {
            this.form.id = '';
            this.form.title = '';
            this.form.description = '';
            this.form.banner = '';
            this.previewBanner = '';
            this.form.start_time = '';
            this.form.end_time = '';
            this.form.registration_deadline = '';
            this.form.preliminary_date = '';
            this.form.final_date = '';
            this.form.whatsapp_group_link = '';
            this.form.guidebook_link = '';
            this.form.location = '';
            this.form.is_online = '0';
            this.form.link_zoom = '';
            this.form.quota = 0;
        },

        handleFileChange(event: Event & { target: HTMLInputElement }): void {
            const target = event.target as HTMLInputElement;
            if (target.files && target.files[0]) {
                this.form.banner = target.files[0];
                const reader = new FileReader();
                reader.onload = () => {
                    this.previewBanner = reader.result as string;
                }
                reader.readAsDataURL(target.files[0]);
            }
        },

        handleCloseModal(): void {
            this.showModal = false;
            this.hanldeResetForm();
        },

        toggleSelectAll(event: Event[]): void {
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

        syncCheckedAll(events: Event[]): void {
            this.checkedAll = events.length > 0 && events.every(event => this.selectedIds.includes(event.id));
        },

        async handleDeleteAll() {
            this.isLoading = true;
            const method = 'post';
            const url = `/apiadmin/dashboard/apiadmin/dashboard/event/delete-all `
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
                    this.events = this.events.filter(event => !deleteIds.includes(event.id));
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