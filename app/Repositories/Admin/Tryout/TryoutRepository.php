<?php

namespace App\Repositories\Admin\Tryout;

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
    public function getTryouts(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $event_id = $payload['event_id'];
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $paginate, $event_id) {
            return $this->model::with(['event:id,title'])
                ->where('event_id', $event_id)
                ->filter($search)
                ->paginate($paginate)
                ->through(function ($item) {
                    if ($item->relationLoaded('event') && $item->event) {
                        $item->event->setAppends([]);
                    }
                    return $item;
                });
        });
    }
}
