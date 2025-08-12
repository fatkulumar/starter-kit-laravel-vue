<?php

namespace App\Services\Student\Tryout;

interface TryoutServiceInterface
{
    public function getTryoutByEventId(array $paginate): object;
}
