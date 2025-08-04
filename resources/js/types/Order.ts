export interface Order {
    id: string;
    user_id: string;
    order_number: string;
    amount: number;
    status: string;

    order_count: number;
}