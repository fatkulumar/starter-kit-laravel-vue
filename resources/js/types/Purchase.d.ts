import { Order } from "./Order";
import { User } from "@/types";

export interface Purchase {
    id          : string;
    label       : string;
    proof       : string;
    proof_url   : string;
    order       : Order;
}