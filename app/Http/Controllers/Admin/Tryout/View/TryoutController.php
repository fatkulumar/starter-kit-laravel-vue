<?php

namespace App\Http\Controllers\Admin\Tryout\View;

use App\Http\Controllers\Controller;
use App\Services\Admin\Event\EventService;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TryoutController extends Controller
{
    private $tryoutService, $eventService;

    /**
     * Create a new class instance.
     */
    public function __construct(TryoutService $tryoutService, EventService $eventService)
    {
        $this->tryoutService = $tryoutService;
        $this->eventService = $eventService;
    }

    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $eventCode = $request->query('event_code');
        $findEventByEventCode = $this->eventService->findByEventCode($eventCode);
        $eventId = $findEventByEventCode->id;
        $checkEventId = $this->eventService->show($eventId);
        $event = $this->eventService->show($eventId);
        if(!$checkEventId) return redirect()->back();
        return Inertia::render('admin/tryout', [
            'event_id' => $eventId,
            'event' => $event
        ]);
    }

    /**
     * Gift.
     */
    public function gift(Request $request)
    {
        $tryoutCode = $request->query('tryout_code');
        $findTryoutByTryoutCode = $this->tryoutService->findByTryoutCode($tryoutCode);
        return Inertia::render('admin/giftTryout', [
            'tryout' => $findTryoutByTryoutCode
        ]);
    }
}
