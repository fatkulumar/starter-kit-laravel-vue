<?php

namespace App\Repositories\Admin\Tryout;

interface TryoutRepositoryInterface
{
    public function getTryouts(array $payload): object;
}
