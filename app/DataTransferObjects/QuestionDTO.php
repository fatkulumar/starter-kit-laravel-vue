<?php

namespace App\DataTransferObjects;

class QuestionDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $subtestId,
        public readonly ?string $subjectId,
        public readonly string $optionA,
        public readonly string $optionB,
        public readonly string $optionC,
        public readonly string $optionD,
        public readonly string $optionE,
        public readonly string $correctAnswer,
        public readonly string $explanation,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            subtestId: $data['subtest_id'] ?? null,
            subjectId: $data['subject_id'] ?? null,
            optionA: $data['option_a'],
            optionB: $data['option_b'],
            optionC: $data['option_c'],
            optionD: $data['option_d'],
            optionE: $data['option_e'],
            correctAnswer: $data['correct_answer'],
            explanation: $data['explanation'],
        );
    }
}
