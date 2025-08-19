<?php

namespace App\Repositories\Student\Subtest;

use App\Models\Subtest;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SubtestRepository extends Repository implements SubtestRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Subtest $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getSubtestAndQuestionByTryoutId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $minutes  = $payload['minutes'];
        $tryoutId   = $payload['tryout_id'];
        $userId   = $payload['user_id'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($tryoutId, $userId) {
            return $this->model->join('start_tryouts', 'start_tryouts.tryout_id', '=', 'subtests.tryout_id')
                ->join('tryouts', 'tryouts.id', '=', 'subtests.tryout_id')
                ->with(['questions'])
                ->where('subtests.tryout_id', $tryoutId)
                ->where('start_tryouts.user_id', $userId)
                ->select(
                    'subtests.id',
                    'subtests.title',
                    'subtests.amount_question',
                    'subtests.amount_minutes',
                    'start_tryouts.start_at',
                    'start_tryouts.finish_at',
                    'tryouts.duration',
                    DB::raw('DATE_ADD(start_tryouts.start_at, INTERVAL tryouts.duration MINUTE) as end_at')
                )
                ->first();
        });
    }
}
