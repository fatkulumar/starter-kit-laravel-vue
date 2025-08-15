<?php

namespace App\Services\Admin\Purchase;

use App\DataTransferObjects\EventDTO;
use App\DataTransferObjects\PurchaseAdminDTO;
use App\Enums\PaymentStatusEnum;
use App\Repositories\Admin\Order\OrderRepository;
use App\Repositories\Admin\Payment\PaymentRepository;
use App\Repositories\Admin\Purchase\PurchaseRepository;
use App\Services\Service;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Cache;

class PurchaseService extends Service implements PurchaseServiceInterface
{
    use FileUpload;
    private $purchaseRepository, $orderRepository, $paymentRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/purchase/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(PurchaseRepository $purchaseRepository, OrderRepository $orderRepository, PaymentRepository $paymentRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getPurchases(array $payload): object
    {
        return $this->orderRepository->getPurchases($payload);
    }

    /**
     * confirmation many data.
     */
    public function confirmationAll(array $payload): object
    {
        $ids = $payload['ids'];
        $status = $payload['status'];
        foreach ($ids as $id) {
            $data = [
                'id' => $id,
                'status' => $status
            ];
            $order =  $this->orderRepository->updateStatus($data);
            if ($order->status == 'paid') {
                $dataUpdateStatusPayment = [
                    'order_id' => $order->id,
                    'status' => PaymentStatusEnum::SETTLEMENT->value
                ];
                $this->paymentRepository->updateStatus($dataUpdateStatusPayment);
            }
            if ($order->status == 'cancelled') {
                $dataUpdateStatusPayment = [
                    'order_id' => $order->id,
                    'status' => PaymentStatusEnum::CANCEL->value
                ];
                $this->paymentRepository->updateStatus($dataUpdateStatusPayment);
            }
        }
        Cache::flush();
        return $this->orderRepository->getOrderWithPurchaseWhereIn($ids);
    }

    public function confirm(array $data): object
    {
        $order =  $this->orderRepository->updateStatus($data);
        if ($order->status == 'paid') {
            $dataUpdateStatusPayment = [
                'order_id' => $order->id,
                'status' => PaymentStatusEnum::SETTLEMENT->value
            ];
            $this->paymentRepository->updateStatus($dataUpdateStatusPayment);
        }
        if ($order->status == 'cancelled') {
            $dataUpdateStatusPayment = [
                'order_id' => $order->id,
                'status' => PaymentStatusEnum::CANCEL->value
            ];
            $this->paymentRepository->updateStatus($dataUpdateStatusPayment);
        }
        Cache::flush();
        return $this->orderRepository->getOrderWithPurchase($order->id);
    }
}
