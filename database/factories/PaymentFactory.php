<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gateway = $this->faker->randomElement(['midtrans', 'tripay']);
        $method = match ($gateway) {
            'midtrans' => $this->faker->randomElement(['gopay', 'bca_va', 'qris']),
            'tripay' => $this->faker->randomElement(['mandiri_va', 'ovo', 'bri_va']),
            default => 'manual'
        };

        return [
            'id' => (string) Str::uuid(),
            'order_id' => Order::factory(),
            'payment_gateway' => $gateway,
            'payment_method' => $method,
            'reference' => strtoupper(Str::random(12)),
            'amount_paid' => $this->faker->randomFloat(2, 50000, 300000),
            'status' => $this->faker->randomElement([
                'authorize',
                'capture',
                'settlement',
                'pending',
                'deny',
                'cancel',
                'expire',
                'refund',
                'partial_refund',
                'chargeback',
                'partial_chargeback',
                'failure',
            ]),
            'raw_response' => [
                'transaction_time' => now()->toDateTimeString(),
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'gross_amount' => '150000.00',
                'currency' => 'IDR',
                'signature_key' => Str::random(32),
            ],
        ];
    }
}
