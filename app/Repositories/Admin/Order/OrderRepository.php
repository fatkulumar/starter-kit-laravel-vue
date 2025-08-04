<?php

namespace App\Repositories\Admin\Order;

use App\Models\Order;
use App\Repositories\Repository;
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
     * Update or create..
     */
    public function updateOrCreate(array $where, array $data): object
    {
        return $this->model::updateOrCreate($where, $data);
    }

    /**
     * Generate tryout_code.
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
    public function findByTryoutId(string $tryoutId): object
    {
        return $this->model::with(['user'])->where('tryout_id', $tryoutId)->get();
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
}
