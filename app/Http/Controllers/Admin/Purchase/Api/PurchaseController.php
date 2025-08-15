<?php

namespace App\Http\Controllers\Admin\Purchase\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Purchase\PurchasecConfrimAllRequest;
use App\Http\Requests\Admin\Purchase\PurchaseConfirmRequest;
use App\Services\Admin\Purchase\PurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $payload = [
            'search' => $search,
            'cacheKey' => 'purchase_admin:search=' . ($search ?: 'all') . ':purchase_admin_page=' . $page,
            'paginate' => 10,
            'minutes' => 10,
        ];
        $result = $this->purchaseService->getPurchases($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $status = $request->get('status');
        $result = $this->purchaseService->update($status, $id);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the many data.
     */
    // public function deleteAll(PurchaseDeleteAllRequest $request): JsonResponse
    // {
    //     $dataValidate = $request->validated();
    //     $result = $this->purchaseService->destroy($dataValidate['ids']);
    //     $this->setResult($result)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
    //     return $this->toJson();
    // }

    /**
     * Confirm purchase.
     */
    public function confirm(PurchaseConfirmRequest $request): JsonResponse
    {
        $dataValidate = $request->validated();
        $result = $this->purchaseService->confirm($dataValidate);
        $this->setResult($result)->setStatus(true)->setMessage('Berhasil Konfrimasi')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Confirmation all purchase status
     */
    public function confirmationAll(PurchasecConfrimAllRequest $request): JsonResponse
    {
        $dataValidate = $request->validated();
        $result = $this->purchaseService->confirmationAll($dataValidate);
        $this->setResult($result)->setStatus(true)->setMessage('Success Confrim Datas')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
