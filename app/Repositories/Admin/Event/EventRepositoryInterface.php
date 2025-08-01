<?php

namespace App\Repositories\Admin\Event;

interface EventRepositoryInterface
{
    public function getEvents(array $payload): object;
    public function getEventWithTryoutLatest(string $id): object;
    public function getEventWithTryout(string $id): object;
}
