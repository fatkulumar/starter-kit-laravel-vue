<?php

namespace App\Services\Public\Event;

use App\DataTransferObjects\EventDTO;

interface EventServiceInterface
{
    public function getEvents(array $paginate): object;
}
