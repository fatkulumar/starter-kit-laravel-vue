<?php

namespace App\Services\Student\Purchase;

use App\DataTransferObjects\PurchaseDTO;

interface PurchaseServiceInterface
{
    public function store(PurchaseDTO $dto): object;
}
