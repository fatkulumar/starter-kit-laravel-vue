<?php

namespace App\Repositories\Student\Subtest;

interface SubtestRepositoryInterface
{
    public function getSubtestAndQuestionByTryoutId(array $payload): object;
}
