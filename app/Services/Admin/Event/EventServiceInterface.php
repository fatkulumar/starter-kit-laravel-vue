<?php

namespace App\Services\Admin\Event;

use App\DataTransferObjects\EventDTO;

interface EventServiceInterface
{
    public function getEvents(array $paginate): object;
    public function store(EventDTO $eventDTO): object;
    public function update(EventDTO $eventDTO): object;
    public function delete(string $id): bool;
    public function destroy(array $id): array;
}
