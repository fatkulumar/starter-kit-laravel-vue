<?php

namespace App\Services\Admin\Purchase;

use App\DataTransferObjects\PurchaseAdminDTO;

interface PurchaseServiceInterface
{
    public function getPurchases(array $payload): object;
    // public function store(EventDTO $eventDTO): object;
    // public function update(string $status, string $id);
    // : object;
    // public function delete(string $id): bool;
    public function confirmationAll(array $payload): object;
    public function confirm(array $data): object;
    // public function show(string $id): object;
    // public function findByEventCode(string $eventCode): object;
}
