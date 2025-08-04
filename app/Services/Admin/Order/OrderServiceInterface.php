<?php

namespace App\Services\Admin\Order;

use App\DataTransferObjects\OrderDTO;

interface OrderServiceInterface
{
    public function getOrders(array $paginate): object;
    public function store(OrderDTO $orderDTO): object;
}
