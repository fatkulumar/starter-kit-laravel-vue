import type { Event } from "@/types/Event";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";

export type EventListResponse = ApiResponse<PaginatedData<Event>>

export const useEventStore = defineStore('event-student', {
    state: (): {
        events: Event[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        eventCache: Map<string, EventListResponse>,
    } => ({
        events: [] as Event[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        eventCache: new Map<string, EventListResponse>(),
    }),
    actions: {
        async fetchEvents(userId: '', page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const cacheKey = `event_student_${userId}`;

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

                    return;
                }

                const url = `/api/student/get-event-purchased`;

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

                this.eventCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data event' };
            } finally {
                this.isLoading = false;
            }
        }
    }
});