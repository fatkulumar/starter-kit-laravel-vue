import type { Event } from "@/types/Event";
import { defineStore } from "pinia";
import axios from '@/lib/axios'
import type { ApiResponse } from "@/types/ApiResponse";
import type { PaginatedData } from "@/types/PaginatedData";
import type { Pagination } from "@/types/pagination";
import { useTryoutStore } from "./tryoutStore";

export type EventListResponse = ApiResponse<PaginatedData<Event>>

export const useEventStore = defineStore('event-public', {
    state: (): {
        events: Event[],
        isLoading: boolean
        error: Record<string, any> | null,
        pagination: Pagination | null,
        page: number
        searchQuery: string,
        eventsCache: Map<string, EventListResponse>,
        prefixCacheKey: string
    } => ({
        events: [] as Event[],
        isLoading: false,
        error: null,
        pagination: null as Pagination | null,
        page: 1,
        searchQuery: '',
        eventsCache: new Map<string, EventListResponse>(),
        prefixCacheKey: ''
    }),
    actions: {
        // fetch event where publish
        async fetchEvents(page = 1, search?: string): Promise<void> {
            this.isLoading = true;
            this.error = null;

            const searchQuery = search ?? this.searchQuery;

            const isSearching = !!searchQuery;
            const cacheKey = isSearching ? `search_event_public_${searchQuery}` : `public_event_page_all_${page}`;
            this.prefixCacheKey = cacheKey;

            try {
                if (this.eventsCache.has(cacheKey)) {
                    const cached = this.eventsCache.get(cacheKey)!;

                    if (cached && typeof cached === 'object' && 'data' in cached) {
                        const data = cached.data;

                        if (Array.isArray(data)) {
                            this.events = data;
                            this.pagination = null;
                        } else {
                            this.events = data.data;
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
                    ? `/api/public/event?search=${encodeURIComponent(searchQuery)}`
                    : `/api/public/event?page=${page}`;

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

                this.eventsCache.set(cacheKey, response.data);
            } catch (err: any) {
                this.error = err?.response?.data || { message: 'Gagal mengambil data event' };
            } finally {
                this.isLoading = false;
            }
        },

        async deleteCache(): Promise<void> {
            [...this.eventsCache.keys()]
                .filter(key =>
                    key.startsWith(this.prefixCacheKey)
                )
                .forEach(key => this.eventsCache.delete(key));
        },
    }
});