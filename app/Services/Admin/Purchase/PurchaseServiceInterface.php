<?php

namespace App\Services\Admin\Purchase;

use App\DataTransferObjects\PurchaseAdminDTO;

interface PurchaseServiceInterface
{
    public function getPurchases(array $payload): object;
    public function confirmationAll(array $payload): object;
    public function confirm(array $data): object;
}
