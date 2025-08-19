<?php

namespace App\Services\Student\Purchase;

use App\DataTransferObjects\OrderDTO;
use App\DataTransferObjects\PurchaseDTO;
use App\DataTransferObjects\TryoutDTO;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Repositories\Admin\Order\OrderRepository;
use App\Repositories\Admin\Payment\PaymentRepository;
use App\Repositories\Student\Purchase\PurchaseRepository;
use App\Services\Service;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
     * Save data.
     */
    public function store(PurchaseDTO $dto): object
    {
        DB::beginTransaction();
        try {
            $filePaths = [];

            foreach ($dto->tasks as $task) {
                $this->fileSettings();
                $filePath = !empty($task['file']) ? $this->uploadFile($task['file']) : null;
                $filePaths[] = [
                    'label' => $task['label'],
                    'proof' => $filePath,
                ];
            }

            foreach ($dto->tryout_id as $tryout) {
                $whereOrder = [
                    'user_id'   => Auth::id(),
                    'tryout_id' => $tryout,
                ];
                $dataOrder = [
                    'amount'          => $dto->amount,
                    'order_number'    => $this->orderRepository->generateUniqueOrderNumber(),
                    'status'          => OrderStatusEnum::PENDING->value,
                    'payment_gateway' => PaymentGatewayEnum::SELF->value,
                    'payment_method'  => PaymentMethodEnum::SELF->value,
                ];
                $order = $this->orderRepository->updateOrCreate($whereOrder, $dataOrder);

                $this->paymentRepository->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'payment_gateway'   => $order->payment_gateway,
                        'payment_method'    => $order->payment_method,
                        'reference'         => $this->paymentRepository->generateUniquePaymentNumber(),
                        'amount_paid'       => $order->amount,
                        'status'            => PaymentStatusEnum::PENDING->value,
                        'order_number'      => $order->order_number,
                    ]
                );

                // simpan tasks hasil upload sebelumnya
                foreach ($filePaths as $task) {
                    $this->purchaseRepository->store([
                        'order_id' => $order->id,
                        'proof'    => $task['proof'],
                        'label'    => $task['label'],
                    ]);
                }
            }

            DB::commit();
            Cache::flush();

            return (object)[
                'status'  => true,
                'message' => 'Pembelian berhasil, menunggu konfirmasi',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
