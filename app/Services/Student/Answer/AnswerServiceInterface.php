<?php

namespace App\Services\Student\Answer;

use App\DataTransferObjects\AnswerDTO;

interface AnswerServiceInterface
{
    public function getAnswer(array $payload): object;
     public function store(AnswerDTO $dto): object;
}
