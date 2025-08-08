<?php

namespace App\Repositories\Admin\Question;

interface QuestionRepositoryInterface
{
    public function getQuestionBySubtestId(string $subtestId): object;
    public function updateOrCreate(array $where, array $data): object;
}
