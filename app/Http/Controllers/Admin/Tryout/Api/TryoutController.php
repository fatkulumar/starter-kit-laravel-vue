<?php

namespace App\Http\Controllers\Admin\Tryout\Api;

use App\DataTransferObjects\TryoutDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tryout\TryoutDeleteAllRequest;
use App\Http\Requests\Admin\Tryout\TryoutStoreRequest;
use App\Http\Requests\Admin\Tryout\TryoutUpdateRequest;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    private $tryoutService;
    /**
     * Create a new class instance.
     */
    public function __construct(TryoutService $tryoutService)
    {
        $this->tryoutService = $tryoutService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        $event_id = $request->query('event_id');
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $payload = [
            'search' => $search,
            'cacheKey' => 'tryouts:search=' . ($search ?: 'all') . ':page=' . $page . '_event_id_' . $event_id,
            'paginate' => 10,
            'event_id' => $event_id
        ];
        $result = $this->tryoutService->getTryouts($payload);
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
    public function store(TryoutStoreRequest $request): JsonResponse
    {
        $dto = TryoutDTO::fromArray($request->validated());
        $result = $this->tryoutService->store($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
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
    public function update(TryoutUpdateRequest $request, string $id): JsonResponse
    {
        $dto = TryoutDTO::fromArray($request->validated());
        $result = $this->tryoutService->update($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->tryoutService->delete($id);
        $this->setResult($id)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the many data.
     */
    public function deleteAll(TryoutDeleteAllRequest $request): JsonResponse
    {
        $dataValidate = $request->validated();
        $result = $this->tryoutService->destroy($dataValidate['ids']);
        $this->setResult($result)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
