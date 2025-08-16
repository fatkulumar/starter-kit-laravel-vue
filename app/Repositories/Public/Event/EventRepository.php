<?php

namespace App\Repositories\Public\Event;

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
    public function getEventPublish(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () {
            return $this->model::withCount(['tryouts'])
                ->select('id', 'title', 'whatsapp_group_link', 'guidebook_link', 'link_zoom', 'quota')
                ->where('is_publish', 1)
                ->get();
        });
    }
}
