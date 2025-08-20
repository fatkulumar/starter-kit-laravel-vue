<?php

namespace App\Services\Student\Answer;

use App\DataTransferObjects\AnswerDTO;
use App\DataTransferObjects\FinishExamDTO;

interface AnswerServiceInterface
{
    public function getAnswer(array $payload): object;
    public function store(AnswerDTO $dto): object;
    public function finishExam(FinishExamDTO $dto): object;
}
