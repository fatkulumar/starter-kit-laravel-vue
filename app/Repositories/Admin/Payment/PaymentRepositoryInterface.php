<?php

namespace App\Repositories\Admin\Payment;

interface PaymentRepositoryInterface
{
    public function generateUniquePaymentNumber(): string;
}
