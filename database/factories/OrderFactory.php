<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\PaymentMethodEnum;
use App\Models\Tryout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $user = User::where('email', 'umar@gmail.com')->first();
        return [
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'tryout_id' => Tryout::factory(),
            'order_number' => 'order-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'amount' => $this->faker->randomFloat(2, 50000, 300000),
            'status' => OrderStatusEnum::PAID->value,
            'payment_method' => $this->faker->randomElement(PaymentMethodEnum::cases())->value,
            'payment_gateway' => $this->faker->randomElement(PaymentGatewayEnum::cases())->value,
        ];
    }
}
