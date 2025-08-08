<?php

namespace App\Repositories\Admin\Subtest;

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
    public function getSubtestByTryoutId(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $tryoutId = $payload['tryout_id'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate, $tryoutId) {
            return $this->model::where('tryout_id', $tryoutId)->filter($search)->paginate($paginate);
        });
    }

    /**
     * Get subtest by tryout_id.
     */
    public function getSubtestWhereTryoutId(string $tryoutId): object
    {
        return $this->model::where('tryout_id', $tryoutId)->first();
    }

    /**
     * Get data by subtest_code.
     */
    public function getSubtestBySubtestCode(string $subtestCode): object
    {
        return $this->model::where('subtest_code', $subtestCode)->first();
    }
}
