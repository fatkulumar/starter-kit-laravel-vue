<?php

namespace App\Repositories\Admin\Event;

use App\Models\Event;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class EventRepository extends Repository implements EventRepositoryInterface
{
    protected $model;

     /**
     * Create a new class instance.
     */
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getEvents(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $paginate) {
            return $this->model::withCount(['tryouts'])->filter($search)->paginate($paginate);
        });
    }

    /**
     * Get Data Lates.
     */
    public function getEventWithTryoutLatest(string $id): object
    {
        return $this->model::withCount(['tryouts'])->latest()->first();
    }

    /**
     * Show.
     */
    public function getEventWithTryout(string $id): object
    {
        return $this->model::withCount(['tryouts'])->find($id);
    }
}
