<?php

namespace App\Services\Student\Tryout;

use App\Repositories\Student\Tryout\TryoutRepository;
use App\Services\Service;
use App\Traits\FileUpload;

class TryoutService extends Service implements TryoutServiceInterface
{
    use FileUpload;
    private $tryoutRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/tryout/thumbnail/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(TryoutRepository $tryoutRepository)
    {
        $this->tryoutRepository = $tryoutRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getTryoutByEventId(array $payload): object
    {
        return $this->tryoutRepository->getTryoutByEventId($payload);
    }

    /**
     * Get tryout purchased by event_id
     */
    public function getTryoutPurchasedByEventId(array $payload): object
    {
        return $this->tryoutRepository->getTryoutPurchasedByEventId($payload);
    }
}
