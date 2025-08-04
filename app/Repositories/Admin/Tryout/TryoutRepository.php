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
            return $this->model::with(['event:id,title', 'grade:id,name,level,alias'])
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

    /**
     * find by event_id.
     */
    public function findByEventId(string $eventId): object
    {
        return $this->model::where('event_id', $eventId)->first();
    }

    /**
     * find by with grade.
     */
    public function findWithGrade(string $id): object
    {
        return $this->model::with(['grade:id,name,level,alias'])->find($id);
    }

    /**
     * find by tryout_code.
     */
    public function findByTryoutCode(string $tryoutCode): object
    {
        return $this->model::withCount('orders')->where('tryout_code', $tryoutCode)->first(['id', 'title', 'price']);
    }
}
