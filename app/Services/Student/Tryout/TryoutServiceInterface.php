<?php

namespace App\Services\Student\Tryout;

interface TryoutServiceInterface
{
    public function getTryoutByEventId(array $payload): object;
    public function getTryoutPurchasedByEventId(array $payload): object;
}
