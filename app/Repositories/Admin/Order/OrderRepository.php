<?php

namespace App\Repositories\Admin\Order;

use App\Models\Order;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class OrderRepository extends Repository implements OrderRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
     * Generate order number.
     */
    public function generateUniqueOrderNumber(): string
    {
        do {
            $orderNumber = 'order-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while ($this->model::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * find by tryout_id with user.
     */
    public function findByTryoutId(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $tryoutId = $payload['tryout_id'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate, $tryoutId) {
            return $this->model::with(['user'])->where('tryout_id', $tryoutId)->where('status', 'paid')->filter($search)->paginate($paginate);
        });
    }

    /**
     * find by tryout_id withCount user.
     */
    public function findByTryoutIdCount(string $tryoutId): int
    {
        return $this->model::with(['user'])
            ->where('tryout_id', $tryoutId)
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Find by user_id and tryout_id
     */
    public function findOrderByUserIdTryoutId(string $userId, string $tryoutId): object
    {
        return $this->model::with(['purchase:id,order_id,proof'])
            ->where('user_id', $userId)
            ->where('tryout_id', $tryoutId)
            ->first(['id', 'user_id', 'tryout_id']);
    }

    /**
     * Get for purchase admin.
     */
    public function getPurchases(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate) {
            return $this->model::with([
                    'user:id,name,email',
                    'purchases:id,order_id,proof',
                    'tryout:id,title'
                ])
                ->select('id', 'user_id', 'tryout_id', 'amount', 'status')
                ->filter($search)
                ->paginate($paginate);
        });
    }
}
