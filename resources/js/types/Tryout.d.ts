export interface Tryout {
    id: string;
    event_id: string;
    title: string;
    description?: string;
    start_time: string;
    end_time: string;
    duration: number;
    is_active: boolean;
    is_locked: boolean;
    cover_image?: string;
    guide_link?: string;
    created_at: string;
    updated_at: string;
}