<?php

namespace App\Repositories\Student\Tryout;

use App\Enums\OrderStatusEnum;
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
            return $this->model
                ->with(['orders:id,user_id,tryout_id', 'orders.user' => function ($query) {
                    $query->select('id')->setEagerLoads([]); // hapus eager loading otomatis di User
                }])
                ->where('event_id', $event_id)
                ->select('id', 'title', 'price')
                ->get(['title'])
                ->each(function ($item) {
                    $item->orders->each(function ($order) {
                        $order->user->makeHidden('role');
                    });
                });
        });
    }

    /**
     * get tryouts pruchased by event_id
     */
    public function getTryoutPurchasedByEventId(array $payload): object
    {
        $cacheKey = $payload['cacheKey'];
        $event_id = $payload['event_id'];
        $minutes = $payload['minutes'];

        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($event_id) {
            return $this->model::join('orders', 'orders.tryout_id', '=', 'tryouts.id')
                ->where('orders.status', OrderStatusEnum::PAID->value)
                ->where('tryouts.event_id', $event_id)
                ->select('tryouts.id', 'tryouts.title', 'tryouts.end_time')
                ->get();
        });
    }
}
