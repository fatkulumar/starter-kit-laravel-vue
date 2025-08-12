<?php

namespace App\Repositories\Student\Tryout;

use App\Models\Tryout;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class TryoutRepository extends Repository implements TryoutRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Tryout $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getTryoutByEventId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $event_id = $payload['event_id'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($event_id) {
            return $this->model::where('event_id', $event_id)
                ->select('id', 'title', 'price')
                ->get()->each->withoutAppends(['thumbnail_url']);
        });
    }
}
