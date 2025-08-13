<?php

namespace App\Http\Controllers\Public\Event\Api;

use App\Http\Controllers\Controller;
use App\Services\Public\Event\EventService;
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
    public function getEventPublish(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $payload = [
            'cacheKey' => 'events_public:search=' . ($search ?: 'all') . ':page=' . $page . '_public',
            'minutes' => 10,
        ];
        $result = $this->eventService->getEventPublish($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
