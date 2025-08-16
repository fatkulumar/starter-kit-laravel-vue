<?php

namespace App\Http\Controllers\Student\Event\Api;

use App\Http\Controllers\Controller;
use App\Services\Student\Event\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function getEventPurchased(Request $request)
    {
        $userId = Auth::user()->id;
        $payload = [
            'cacheKey' => "events_student_purchased . $userId",
            'minutes' => 10,
            'user_id' => $userId
        ];
        $result = $this->eventService->getEventPurchased($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
