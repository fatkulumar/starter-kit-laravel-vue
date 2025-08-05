<?php

namespace App\Services\Admin\Order;

use App\DataTransferObjects\GiftTryoutDTO;
use App\DataTransferObjects\OrderDTO;

interface OrderServiceInterface
{
    public function getOrders(array $paginate): object;
    public function store(OrderDTO $orderDTO): object;
    public function giftTryout(GiftTryoutDTO $orderDTO, array $payload): object;
    public function hasOrderTryout(array $payload): object;
}
