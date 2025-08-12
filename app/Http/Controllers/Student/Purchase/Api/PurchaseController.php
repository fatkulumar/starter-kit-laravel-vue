<?php

namespace App\Http\Controllers\Student\Purchase\Api;

use App\DataTransferObjects\OrderDTO;
use App\DataTransferObjects\PurchaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderStoreRequest;
use App\Http\Requests\Admin\Purchase\PurchaseStoreRequest;
use App\Services\Student\Purchase\PurchaseService;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    private $purchaseService;
    /**
     * Create a new class instance.
     */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseStoreRequest $request) : JsonResponse
    {
        $dto = PurchaseDTO::fromArray($request->validated());
        $result = $this->purchaseService->store($dto);
        $this->setResult($result)->setStatus(true)->setMessage($result->message)->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
