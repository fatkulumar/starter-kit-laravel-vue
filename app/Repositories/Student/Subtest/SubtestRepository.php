<?php

namespace App\Repositories\Student\Subtest;

use App\Models\Subtest;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

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

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($tryoutId) {
            return $this->model
                ->with(['questions'])
                ->where('tryout_id', $tryoutId)
                ->select('id', 'title', 'amount_question', 'amount_minutes')
                ->first();
        });
    }
}
