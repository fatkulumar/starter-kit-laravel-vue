<?php

namespace App\Repositories\Public\Tryout;

interface TryoutRepositoryInterface
{
    public function getTryoutByEventId(array $payload): object;
}
