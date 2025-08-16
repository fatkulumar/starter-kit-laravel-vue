<?php

namespace App\Http\Controllers\Student\Tryout\Api;

use App\Http\Controllers\Controller;
use App\Services\Student\Tryout\TryoutService;
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
    public function getTryoutByEventId(Request $request): JsonResponse
    {
        $event_id = $request->query('event_id');
        $payload = [
            'cacheKey' => 'tryouts_student_by_event_id=' . ($event_id ?: '_student'),
            'minutes' => 10,
            'event_id' => $event_id
        ];
        $result = $this->tryoutService->getTryoutByEventId($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Get tryouts purchased by event_id
     */
    public function getTryoutPurchasedByEventId(Request $request): JsonResponse
    {
        $event_id = $request->query('event_id');
        $payload = [
            'cacheKey' => 'tryouts_student_by_event_id=' . ($event_id ?: '_student'),
            'minutes' => 10,
            'event_id' => $event_id
        ];
        $result = $this->tryoutService->getTryoutPurchasedByEventId($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
