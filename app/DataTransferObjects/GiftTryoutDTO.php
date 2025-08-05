<?php

namespace App\DataTransferObjects;

use App\Enums\OrderStatusEnum;
use Illuminate\Support\Str;

class GiftTryoutDTO
{
    public function __construct(
        public readonly array $user_id,
        public readonly string $tryout_id,
        public readonly string $order_number,
        public readonly float $amount,
        public readonly OrderStatusEnum $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            tryout_id: $data['tryout_id'],
            order_number: $data['order_number'] ?? 'order-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            amount: (float) $data['amount'],
            status: OrderStatusEnum::from($data['status'] ?? 'pending'),
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'tryout_id' => $this->tryout_id,
            'order_number' => $this->order_number,
            'amount' => $this->amount,
            'status' => $this->status->value,
        ];
    }
}
