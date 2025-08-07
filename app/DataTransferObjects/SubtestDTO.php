<?php

namespace App\DataTransferObjects;

class SubtestDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $tryout_id,
        public readonly string $title,
        public readonly int $amount_question,
        public readonly int $amount_minutes,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            tryout_id: $data['tryout_id'],
            title: $data['title'],
            amount_question: $data['amount_question'] ?? null,
            amount_minutes: $data['amount_minutes'] ?? null,
        );
    }
}
