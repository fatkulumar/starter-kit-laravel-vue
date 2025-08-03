<?php

namespace App\Repositories\Admin\Tryout;

interface TryoutRepositoryInterface
{
    public function getTryouts(array $payload): object;
    public function findByEventId(string $eventId): object;
    public function findWithGrade(string $id): object;
}
