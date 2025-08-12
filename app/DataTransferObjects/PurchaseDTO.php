<?php

namespace App\DataTransferObjects;

class PurchaseDTO
{
    public array $tasks; // key: label, file

    public function __construct(
        public array $tryout_id,
        public string $amount,
        public ?string $label = null,
        public ?string $proof = null,
        array $tasks = []
    ) {
        $this->tasks = $tasks;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            tryout_id: $data['tryout_id'],
            amount: $data['amount'],
            label: $data['label'] ?? null,
            proof: $data['proof'] ?? null,
            tasks: $data['tasks'] ?? []
        );
    }
}
