<?php

namespace App\DataTransferObjects;

class PurchaseAdminDTO
{
    public function __construct(
        public array $id, // order_id
        public string $status,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
        );
    }
}
