<?php

namespace App\Services\Admin\Question;

use App\DataTransferObjects\QuestionDTO;

interface QuestionServiceInterface
{
    public function getSubtestBySubtestCode(string $subtestCode): object;
    public function getQuestionBySubtestId(string $subtestId): object;
     public function store(QuestionDTO $subtestDTO): object;
}
