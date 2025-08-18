<?php

namespace App\Services\Student\Tryout;

use App\Repositories\Student\StartTryout\StartTryoutRepository;
use App\Repositories\Student\Subtest\SubtestRepository;
use App\Repositories\Student\Tryout\TryoutRepository;
use App\Services\Service;
use App\Traits\FileUpload;

class TryoutService extends Service implements TryoutServiceInterface
{
    use FileUpload;
    private $tryoutRepository, $startTryoutRepository, $subtestRepository;

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
    public function __construct(TryoutRepository $tryoutRepository, StartTryoutRepository $startTryoutRepository, SubtestRepository $subtestRepository)
    {
        $this->tryoutRepository = $tryoutRepository;
        $this->startTryoutRepository = $startTryoutRepository;
        $this->subtestRepository = $subtestRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getTryoutByEventId(array $payload): object
    {
        return $this->tryoutRepository->getTryoutByEventId($payload);
    }

    /**
     * Get tryout purchased by event_id.
     */
    public function getTryoutPurchasedByEventId(array $payload): object
    {
        return $this->tryoutRepository->getTryoutPurchasedByEventId($payload);
    }

    /**
     * Get tryout tryout_code.
     */
    public function getTryoutByTryoutCode(array $payload): object
    {
        return $this->tryoutRepository->getTryoutByTryoutCode($payload);
    }

    /**
     * insert user_id and tryout_id.
     */
    public function startTryout(array $payload): object
    {
        $tryout = $this->tryoutRepository->getTryoutByTryoutCode($payload);
        $where = [
            'user_id' => $payload['user_id'],
            'tryout_id' => $tryout->id,
        ];
        $data = [
            'user_id' => $payload['user_id'],
            'tryout_id' => $tryout->id,
            'start'=> now(),
        ];
        return $this->startTryoutRepository->updateOrCreate($where, $data);
    }

    /**
     * get subtests tryout
     */
    public function getSubtestsAndQuestionByTryoutCode(array $payload): object
    {
        $tryout = $this->tryoutRepository->getTryoutByTryoutCode($payload);
        $payload['tryout_id'] = $tryout->id;
        return $this->subtestRepository->getSubtestAndQuestionByTryoutId($payload);
    }
}
