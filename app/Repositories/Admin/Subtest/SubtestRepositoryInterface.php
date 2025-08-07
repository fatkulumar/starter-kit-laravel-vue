<?php

namespace App\Repositories\Admin\Subtest;

interface SubtestRepositoryInterface
{
    public function getSubtestByTryoutId(array $payload): object;
    public function getSubtestWhereTryoutId(string $tryoutId): object;
}
