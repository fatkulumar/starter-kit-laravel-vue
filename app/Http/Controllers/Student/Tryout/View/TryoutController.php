<?php

namespace App\Http\Controllers\Student\Tryout\View;

use App\Enums\TryoutDoingStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\Student\Event\EventService;
use App\Services\Student\Tryout\TryoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TryoutController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request, string $eventCode)
    {   
        $event = $this->eventService->findByEventCode($eventCode);
        if (!$event) redirect()->back();
        return Inertia::render('student/tryout/Tryout', [
            'event' => $event
        ]);
    }

     /**
     * doing tryout.
     */
    public function doingTryout(Request $request, string $tryoutCode)
    {   
        // return 'doingTryout';
        $payload = [
            'cacheKey' => 'get_subtest_tryout=' . ($tryoutCode ?: '_student'),
            'paginate' => 10,
            'minutes' => 10,
            'tryout_code' => $tryoutCode,
            'user_id' => Auth::user()->id,
            'search' => $request->query('search')
        ];
        $result = $this->tryoutService->getSubtestsAndQuestionByTryoutCode($payload);
        return Inertia::render('student/tryout/Doing', [
            'result' => $result
        ]);
    }
}
