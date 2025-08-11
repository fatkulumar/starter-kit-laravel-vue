<?php

namespace App\Repositories\Public\Event;

interface EventRepositoryInterface
{
    public function getEvents(array $payload): object;
}
