<?php

namespace App\Repositories\Admin\Purchase;

interface PurchaseRepositoryInterface
{
    public function getPurchases(array $payload): object;
}
