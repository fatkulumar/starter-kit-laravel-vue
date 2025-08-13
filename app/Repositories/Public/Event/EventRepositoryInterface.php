<?php

namespace App\Repositories\Public\Event;

interface EventRepositoryInterface
{
    public function getEventPublish(array $payload): object;
}
