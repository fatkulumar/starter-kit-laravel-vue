<?php

namespace App\Repositories\Student\Tryout;

interface TryoutRepositoryInterface
{
    public function getTryoutByEventId(array $payload): object;
    public function getTryoutPurchasedByEventId(array $payload): object;
    public function getQuestionByTryoutId(array $payload): object;
}
