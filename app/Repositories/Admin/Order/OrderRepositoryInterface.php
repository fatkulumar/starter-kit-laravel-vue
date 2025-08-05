<?php

namespace App\Repositories\Admin\Order;

interface OrderRepositoryInterface
{
    public function updateOrCreate(array $where, array $data): object;
    public function generateUniqueOrderNumber(): string;
    public function findByTryoutId(array $payload): object;
    public function findByTryoutIdCount(string $tryoutId): int;
}
