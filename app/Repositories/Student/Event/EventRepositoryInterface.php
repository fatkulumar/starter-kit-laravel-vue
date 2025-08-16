<?php

namespace App\Repositories\Student\Event;

interface EventRepositoryInterface
{
    public function getEventPurchased(array $payload): object;
    public function findByEventCode(string $eventCode): object;
}
