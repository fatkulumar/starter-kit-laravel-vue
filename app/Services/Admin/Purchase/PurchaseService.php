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
     * Create data.
     */
    // public function store(EventDTO $dto): object
    // {
    //     $data = [
    //         'title' => $dto->title,
    //         'description' => $dto->description,
    //         'start_time' => $dto->start_time,
    //         'end_time' => $dto->end_time,
    //         'registration_deadline' => $dto->registration_deadline,
    //         'preliminary_date' => $dto->preliminary_date,
    //         'final_date' => $dto->final_date,
    //         'whatsapp_group_link' => $dto->whatsapp_group_link,
    //         'guidebook_link' => $dto->guidebook_link,
    //         'location' => $dto->location,
    //         'link_zoom' => $dto->link_zoom,
    //         'quota' => $dto->quota,
    //         'is_publish' => $dto->is_publish,
    //     ];


    //     $uploadBanner = null;

    //     if ($dto->banner instanceof \Illuminate\Http\UploadedFile) {
    //         $this->fileSettings();
    //         $uploadBanner = $this->uploadFile($dto->banner);
    //     } else {
    //         $uploadBanner = null;
    //     }

    //     $data['banner'] = $uploadBanner;

    //     $purchaseRepository = $this->purchaseRepository->store($data);

    //     Cache::flush();

    //     return $this->purchaseRepository->getEventWithTryoutLatest($purchaseRepository->id);
    // }


    /**
     * update data.
     */
    // public function update(string $status, string $id)
    // // : object
    // {
    //     return $purchaseRepository = $this->orderRepository->getOrderWithPurchase($id);

    //     $updateData = [];

    //     if ($status !== null) $updateData['status'] = $status;

    //     $purchaseRepository->fill($updateData);

    //     $purchaseRepository->save();

    //     Cache::flush();

    //     return $this->purchaseRepository->getOrderWithPurchase($dto->id);
    // }


    /**
     * delete one data.
     */
    // public function delete(string $id): bool
    // {
    //     $data = $this->orderRepository->getOrderWithPurchase($id);
    //     $purchases = $data->purchases;
    //     if ($purchases) {
    //         foreach ($purchases as $item) {
    //             $this->fileSettings();
    //             if ($this->isFileExists($item->proof)) {
    //                 $this->deleteFile($item->proof);
    //             }
    //         }
    //     }
    //     Cache::flush();
    //     return $data->delete($id);
    // }

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
    /**
     * find.
     */
    // public function show(string $id): object
    // {
    //     return $this->purchaseRepository->show($id);
    // }

    /**
     * Find by event_code
     */
    // public function findByEventCode(string $eventCode): object
    // {
    //     return $this->purchaseRepository->findByEventCode($eventCode);
    // }
}
