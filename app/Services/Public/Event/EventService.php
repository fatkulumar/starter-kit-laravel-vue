<?php

namespace App\Services\Public\Event;

use App\Repositories\Public\Event\EventRepository;
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
            'path'        => 'upload/event/thumbnail/',
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
     * List data paginate and search.
     */
    public function getEvents(array $payload): object
    {
        return $this->eventRepository->getEvents($payload);
    }
}
