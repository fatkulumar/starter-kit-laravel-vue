<?php

namespace App\Repositories\Admin\Payment;

interface PaymentRepositoryInterface
{
    public function generateUniquePaymentNumber(): string;
    public function updateStatus(array $data): bool;
}
