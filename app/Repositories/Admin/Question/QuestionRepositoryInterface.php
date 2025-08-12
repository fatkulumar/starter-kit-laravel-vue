<?php

namespace App\Repositories\Admin\Question;

interface QuestionRepositoryInterface
{
    public function getQuestionBySubtestId(string $subtestId): object;
}
