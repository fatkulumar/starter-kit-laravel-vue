<?php

namespace App\Repositories\Admin\Order;

interface OrderRepositoryInterface
{
    public function updateOrCreate(array $where, array $data): object;
    public function generateUniqueOrderNumber(): string;
    public function findByTryoutId(string $tryoutId): object;
    public function findByTryoutIdCount(string $tryoutId): int;
}
