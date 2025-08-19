<?php

namespace App\Services\Student\StartTryout;

use App\Repositories\Student\StartTryout\StartTryoutRepository;
use App\Repositories\Student\Tryout\TryoutRepository;
use App\Services\Service;
use App\Services\Student\StartTryout\StartTryoutServiceInterface;

class StartTryoutService extends Service implements StartTryoutServiceInterface
{
    private $startTryoutRepository, $tryoutRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(TryoutRepository $tryoutRepository, StartTryoutRepository $startTryoutRepository)
    {
        $this->tryoutRepository = $tryoutRepository;
        $this->startTryoutRepository = $startTryoutRepository;
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
            'start_at'=> now(),
        ];
        return $this->startTryoutRepository->updateOrCreate($where, $data);
    }
}
