<?php

namespace App\Http\Controllers\Admin\Order\Api;

use App\DataTransferObjects\GiftTryoutDTO;
// use App\DataTransferObjects\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gift\GiftStoreRequest;
use App\Http\Requests\Admin\Order\OrderStoreRequest;
use App\Services\Admin\Order\OrderService;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderService, $tryoutService;
    /**
     * Create a new class instance.
     */
    public function __construct(OrderService $orderService, TryoutService $tryoutService)
    {
        $this->orderService = $orderService;
        $this->tryoutService = $tryoutService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(OrderStoreRequest $request)
    {
        // $dto = OrderDTO::fromArray($request->validated());
        // $result = $this->orderService->store($dto);
        // $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        // return $this->toJson();
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Store gift tryouts.
     */
    public function giftTryout(Request $req, GiftStoreRequest $request)
    {
        $search = $req->post('search');
        $page = $req->post('page', 1);
        $payload = [
            'search' => $search,
            'cacheKey' => 'users_not_has_tryout_admin:search=' . ($search ?: 'all') . ':page=' . $page,
            'paginate' => 10,
            'minutes' => 10,
        ];
        $dto = GiftTryoutDTO::fromArray($request->validated());
        $result = $this->orderService->giftTryout($dto, $payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

     /**
     * List has oder tryout.
     */
    public function hasOrderTryout(Request $request)
    {
        $search = $request->query('search');
        $tryoutId =  $request->query('tryout_id');
        $page = $request->query('page', 1);
        $payload = [
            'search' => $search,
            'cacheKey' => 'has_order_admin:search=' . ($search ?: 'all') . ':page=' . $page . '_' . $tryoutId,
            'paginate' => 10,
            'tryout_id' => $request->query('tryout_id')
        ];
        $result = $this->orderService->hasOrderTryout($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
