<?php

namespace App\Repositories\Student\Answer;

use App\Models\Answer;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class AnswerRepository extends Repository implements AnswerRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Answer $model)
    {
        $this->model = $model;
    }

    /**
     * List by subtest_id
     */
    public function getBySubtestId(array $payload): object
    {
        $subtestId = $payload['subtest_id'];
        $minutes = $payload['minutes'];
        $cacheKey = $payload['cacheKey'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($subtestId) {
            return $this->model::where('subtest_id', $subtestId)->get();
        });        
    }
}
