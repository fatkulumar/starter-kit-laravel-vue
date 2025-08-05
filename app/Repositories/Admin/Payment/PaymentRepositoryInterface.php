<?php

namespace App\Repositories\Admin\Payment;

interface PaymentRepositoryInterface
{
    public function updateOrCreate(array $where, array $data): object;
    public function generateUniquePaymentNumber(): string;
}
