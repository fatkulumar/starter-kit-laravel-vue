<?php

namespace App\Services\Admin\Order;

use App\DataTransferObjects\OrderDTO;
use App\Repositories\Admin\Order\OrderRepository;
use App\Services\Admin\Order\OrderServiceInterface;
use App\Services\Service;
use Illuminate\Support\Str;

class OrderService extends Service implements OrderServiceInterface
{
    private $orderRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getOrders(array $payload): object
    {
        return $this->orderRepository->all($payload);
    }

    /**
     * Store.
     */
    public function store(OrderDTO $dto): object
    {
        foreach ($dto->user_id as $userId) {
            $where = [
                'user_id'       => $userId,
                'tryout_id'     => $dto->tryout_id,
            ];
            $data = [
                'amount'        => $dto->amount,
                'order_number'  => $this->orderRepository->generateUniqueOrderNumber(),
                'status'        => $dto->status->value,
            ];
            $order = $this->orderRepository->updateOrCreate($where,$data);
        }
        return (object)[
            'order_count' => $this->orderRepository->findByTryoutIdCount($dto->tryout_id)
        ];
    }
}
