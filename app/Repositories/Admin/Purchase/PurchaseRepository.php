<?php

namespace App\Repositories\Admin\Purchase;

use App\Models\Purchase;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Cache;

class PurchaseRepository extends Repository implements PurchaseRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Purchase $model)
    {
        $this->model = $model;
    }

    /**
     * List all data pagninate.
     */
    public function getPurchases(array $payload): object
    {
        $search = $payload['search'];
        $cacheKey = $payload['cacheKey'];
        $paginate = $payload['paginate'];
        $minutes = $payload['minutes'];
        return Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($search, $paginate) {
            return $this->model::with([
                'order:id,user_id,tryout_id,amount,status',
                'order.tryout:id,title',
                'order.user' => function ($q) {
                    $q->select('id', 'name', 'email')->without('profile');
                }
            ])
                ->select('id', 'order_id', 'created_at', 'updated_at', 'proof')
                ->filter($search)
                ->paginate($paginate)
                ->through(function ($purchase) {
                    if ($purchase->relationLoaded('order') && $purchase->order->relationLoaded('tryout')) {
                        $purchase->order->tryout->setAppends([]); // hapus appends aktif
                    }
                    return $purchase;
                });;
        });
    }
}
