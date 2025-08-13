import { User } from ".";
import { Purchase } from "./Purchase";
import { Tryout } from "./Tryout";

export interface Order {
    id          : string;
    user_id     : string;
    order_number: string;
    amount      : number;
    status      : string;

    order_count : number;
    user?       : User;
    tryout?     : Tryout;
    purchases   : Purchase[];
}