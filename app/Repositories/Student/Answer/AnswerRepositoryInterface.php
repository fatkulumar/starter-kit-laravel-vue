<?php

namespace App\Repositories\Student\Answer;

interface AnswerRepositoryInterface
{
    public function getBySubtestId(array $payload): object;
}
