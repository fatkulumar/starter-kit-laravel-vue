<?php

namespace App\Http\Controllers\Admin\Event\Api;

use App\DataTransferObjects\EventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Event\EventDeleteAllRequest;
use App\Http\Requests\Admin\Event\EventStoreRequest;
use App\Http\Requests\Admin\Event\EventUpdateRequest;
use App\Services\Admin\Event\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $eventService;
    /**
     * Create a new class instance.
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
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
            'cacheKey' => 'events:search=' . ($search ?: 'all') . ':page=' . $page,
            'paginate' => 10
        ];
        $result = $this->eventService->getEvents($payload);
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
    public function store(EventStoreRequest $request): JsonResponse
    {
        $dto = EventDTO::fromArray($request->validated());
        $result = $this->eventService->store($dto);
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
    public function update(EventUpdateRequest $request, string $id): JsonResponse
    {
        $dto = EventDTO::fromArray($request->validated());
        $result = $this->eventService->update($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->eventService->delete($id);
        $this->setResult($id)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the many data.
     */
    public function deleteAll(EventDeleteAllRequest $request): JsonResponse
    {
        $dataValidate = $request->validated();
        $result = $this->eventService->destroy($dataValidate['ids']);
        $this->setResult($result)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
