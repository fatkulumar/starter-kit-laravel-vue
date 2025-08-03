import { Grade } from "./Grade";

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
    thumbnail?: string;
    guide_link?: string;
    price: number;
    grade_id: string;
    grade: Grade;
    created_at: string;
    updated_at: string;

    thumbnail_url: string;
    start_time_formatted: string;
    end_time_formatted: string;
}