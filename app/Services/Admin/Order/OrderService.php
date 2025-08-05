<?php

namespace App\Services\Admin\Order;

use App\DataTransferObjects\GiftTryoutDTO;
use App\DataTransferObjects\OrderDTO;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Repositories\Admin\Order\OrderRepository;
use App\Repositories\Admin\Payment\PaymentRepository;
use App\Repositories\Admin\User\UserRepository;
use App\Services\Admin\Order\OrderServiceInterface;
use App\Services\Service;
use Illuminate\Support\Facades\DB;

class OrderService extends Service implements OrderServiceInterface
{
    private $orderRepository, $userRepository, $paymentRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository, PaymentRepository $paymentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
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
        $create = [];
        foreach ($dto->tryout_id as $tryoutId) {
            $where = [
                'user_id'       => $dto->user_id,
                'tryout_id'     => $tryoutId,
            ];
            $data = [
                'amount'        => $dto->amount,
                'order_number'  => $this->orderRepository->generateUniqueOrderNumber(),
                'status'        => $dto->status->value,
            ];
            $order = $this->orderRepository->updateOrCreate($where, $data);
            $create = $order;
        }
        return (object)[
            'data' => $create
        ];
    }

    /**
     * Gift Tryout.
     */
    public function giftTryout(GiftTryoutDTO $dto, array $payload): object
    {
        DB::beginTransaction();

        try {
            foreach ($dto->user_id as $userId) {
                $whereOrder = [
                    'user_id'           => $userId,
                    'tryout_id'         => $dto->tryout_id,
                ];
                $dataOrder = [
                    'amount'            => $dto->amount,
                    'order_number'      => $this->orderRepository->generateUniqueOrderNumber(),
                    'status'            => OrderStatusEnum::PAID->value,
                    'payment_gateway'   => PaymentGatewayEnum::ADMIN->value,
                    'payment_method'    => PaymentMethodEnum::ADMIN->value,
                ];
                $order = $this->orderRepository->updateOrCreate($whereOrder, $dataOrder);

                $wherePayment = [
                    'order_id' => $order->id,
                ];
                $dataPayment = [
                    'payment_gateway'   => $order->payment_gateway,
                    'payment_method'    => $order->payment_method,
                    'reference'         => $this->paymentRepository->generateUniquePaymentNumber(),
                    'amount_paid'       => $order->amount,
                    'status'            => PaymentStatusEnum::SETTLEMENT->value,
                    'order_number'      => $order->order_number,
                ];
                $this->paymentRepository->updateOrCreate($wherePayment, $dataPayment);
            }

            DB::commit();

            return (object)[
                'order_count' => $this->orderRepository->findByTryoutIdCount($dto->tryout_id),
                'userNotHasTryout' => $this->userRepository->userNotHasTryout($payload)
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * List users ordered tryout.
     */
    public function hasOrderTryout(array $payload): object
    {
        return $this->orderRepository->findByTryoutId($payload);
    }
}
