<?php

namespace App\Http\Controllers\Student\Tryout\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Subtest\SubtestRepository;
use App\Services\Student\Tryout\TryoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TryoutController extends Controller
{
    private $tryoutService, $subtestRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(TryoutService $tryoutService, SubtestRepository $subtestRepository)
    {
        $this->subtestRepository = $subtestRepository;
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
     * Get tryouts purchased by event_id.
     */
    public function getTryoutPurchasedByEventId(Request $request): JsonResponse
    {
        $event_id = $request->query('event_id');
        $payload = [
            'cacheKey' => 'tryouts_student_by_event_id=' . ($event_id ?: '_student'),
            'minutes' => 10,
            'event_id' => $event_id,
            'user_id' => Auth::user()->id,
        ];
        $result = $this->tryoutService->getTryoutPurchasedByEventId($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Get subtests and questions.
     */
    public function getSubtestsAndQuestionByTryoutCode(Request $request, $tryoutCode): JsonResponse
    {
        $payload = [
            'cacheKey' => 'get_subtest_tryout=' . ($tryoutCode ?: '_student'),
            'paginate' => 10,
            'minutes' => 10,
            'tryout_code' => $tryoutCode,
            'user_id' => Auth::user()->id,
            'search' => $request->query('search')
        ];
        $result = $this->tryoutService->getSubtestsAndQuestionByTryoutCode($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
