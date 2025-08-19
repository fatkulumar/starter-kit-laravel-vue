<?php

namespace App\DataTransferObjects;

use Illuminate\Http\UploadedFile;

class AnswerDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $subtest_id,
        public readonly string $question_id,
        public readonly string $user_id,
        public readonly string $answer,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            subtest_id: $data['subtest_id'],
            question_id: $data['question_id'],
            user_id: $data['user_id'],
            answer: $data['answer'],
        );
    }
}
