<?php

namespace App\DataTransferObjects;

use App\Enums\PaymentStatusEnum;

class PaymentDTO
{
    public function __construct(
        public readonly string $order_id,
        public readonly string $payment_gateway,
        public readonly string $payment_method,
        public readonly ?string $reference,
        public readonly ?float $amount_paid,
        public readonly PaymentStatusEnum $status,
        public readonly ?array $raw_response = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            order_id: $data['order_id'],
            payment_gateway: $data['payment_gateway'],
            payment_method: $data['payment_method'],
            reference: $data['reference'] ?? null,
            amount_paid: isset($data['amount_paid']) ? (float) $data['amount_paid'] : null,
            status: PaymentStatusEnum::from($data['status'] ?? 'pending'),
            raw_response: isset($data['raw_response']) ? (array) $data['raw_response'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'payment_gateway' => $this->payment_gateway,
            'payment_method' => $this->payment_method,
            'reference' => $this->reference,
            'amount_paid' => $this->amount_paid,
            'status' => $this->status->value,
            'raw_response' => $this->raw_response,
        ];
    }
}
