<?php

namespace App\Http\Controllers\Student\StartTryout\Api;

use App\Http\Controllers\Controller;
use App\Services\Student\StartTryout\StartTryoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StartTryoutController extends Controller
{
    private $startTryoutService;
    /**
     * Create a new class instance.
     */
    public function __construct(StartTryoutService $startTryoutService)
    {
        $this->startTryoutService = $startTryoutService;
    }

    /**
     * Insert user_id and tryout_id in start_tryouts.
     */
    public function startTryout(Request $request): JsonResponse
    {
        $payload = [
            'user_id' => Auth::user()->id,
            'tryout_code' => $request->post('tryout_code'),
        ];
        $result = $this->startTryoutService->startTryout($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
