<?php

namespace App\Services\Admin\Purchase;

use App\DataTransferObjects\EventDTO;

interface PurchaseServiceInterface
{
    public function getPurchases(array $payload): object;
    public function store(EventDTO $eventDTO): object;
    public function update(EventDTO $eventDTO): object;
    public function delete(string $id): bool;
    public function destroy(array $id): array;
    public function show(string $id): object;
    public function findByEventCode(string $eventCode): object;
}
