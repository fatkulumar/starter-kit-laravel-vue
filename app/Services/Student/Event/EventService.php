<?php

namespace App\Services\Student\Event;

use App\Repositories\Student\Event\EventRepository;
use App\Services\Service;
use App\Traits\FileUpload;

class EventService extends Service implements EventServiceInterface
{
    use FileUpload;
    private $eventRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/event/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * List data.
     */
    public function getEventPurchased(array $payload): object
    {
        return $this->eventRepository->getEventPurchased($payload);
    }

     /**
     * Find by event_code
     */
    public function findByEventCode(string $eventCode): object
    {
        return $this->eventRepository->findByEventCode($eventCode);
    }
}
