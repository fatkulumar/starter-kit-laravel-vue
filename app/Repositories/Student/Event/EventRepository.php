<?php

namespace App\Repositories\Student\Event;

use App\Enums\OrderStatusEnum;
use App\Models\Event;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
     * Get event purchased.
     */
    public function getEventPurchased(array $payload): object
    {
        $minutes = $payload['minutes'];
        $cacheKey = $payload['cacheKey'];
        $userId = $payload['user_id'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($userId) {
            return $this->model::join('tryouts', 'tryouts.event_id', '=', 'events.id')
                ->join('orders', 'orders.tryout_id', '=', 'tryouts.id')
                ->where('orders.user_id', $userId)
                ->where('orders.status', OrderStatusEnum::PAID->value)
                ->get([
                    'events.id',
                    'events.title',
                    'events.link_zoom',
                    'events.event_code',
                    'events.description',
                ])
                ->each(function ($event) {
                    $event->setAppends(['banner_url']);
                })
                ->unique('title')      // pastikan title unik
                ->values();
        });
    }

    /** 
     * Find by event_code
     */
    public function findByEventCode(string $eventCode): object
    {
        return $this->model::where('event_code', $eventCode)->first();
    }
}
