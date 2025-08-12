<?php

namespace App\Repositories\Admin\Payment;

use App\Models\Payment;
use App\Repositories\Repository;
use Illuminate\Support\Str;

class PaymentRepository extends Repository implements PaymentRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

     /**
     * Generate payment number.
     */
    public function generateUniquePaymentNumber(): string
    {
        do {
            $paymentNumber = 'payment-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while ($this->model::where('reference', $paymentNumber)->exists());

        return $paymentNumber;
    }
}
