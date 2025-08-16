<?php

namespace App\Services\Student\Event;

interface EventServiceInterface
{
    public function getEventPurchased(array $payload): object;
    public function findByEventCode(string $eventCode): object;
}
