<?php

namespace App\DataTransferObjects;

class FinishExamDTO
{
    public function __construct(
        public readonly string $user_id,
        public readonly array $answers,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            answers: $data['answers'],
        );
    }
}
