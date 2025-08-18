<?php

namespace App\Http\Controllers\Student\StartTryout\View;

use App\Enums\TryoutDoingStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\Student\Event\EventService;
use App\Services\Student\Tryout\TryoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StartTryoutController extends Controller
{
    private $eventService, $tryoutService;
    /**
     * Create a new class instance.
     */
    public function __construct(EventService $eventService, TryoutService $tryoutService)
    {
        $this->eventService = $eventService;
        $this->tryoutService = $tryoutService;
    }

    /**
     * Start Tryout tryout.
     */
    public function startTryout(Request $request, string $tryoutCode)
    {
        if (!Auth::user()) return redirect()->route('home');
        $payload = [
            'user_id' => Auth::user()->id,
            'tryout_code' => $tryoutCode
        ];
        $tryout = $this->tryoutService->getTryoutByTryoutCode($payload);
        return $tryout->status_tryout;
        if ($tryout->status_tryout == TryoutDoingStatusEnum::ACTIVE->value) {
            return redirect()->route('student.doing.tryout', $tryoutCode);
        } 
        if (!$tryout) return redirect()->back();
        return Inertia::render('student/tryout/Start', [
            'tryout' => $tryout
        ]);
    }
}
