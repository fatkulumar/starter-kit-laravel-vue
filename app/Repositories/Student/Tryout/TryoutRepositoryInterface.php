<?php

namespace App\Repositories\Student\Tryout;

interface TryoutRepositoryInterface
{
    public function getTryoutByEventId(array $payload): object;
}
