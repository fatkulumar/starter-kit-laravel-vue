<?php

namespace App\Repositories\Admin\Order;

interface OrderRepositoryInterface
{
    public function generateUniqueOrderNumber(): string;
    public function findByTryoutId(array $payload): object;
    public function findByTryoutIdCount(string $tryoutId): int;
    public function findOrderByUserIdTryoutId(string $userId, string $tryoutId): object;
    public function getPurchases(array $payload): object;
    public function getOrderWithPurchase(string $orderId): object;
    public function updateStatus(array $data): object;
}
