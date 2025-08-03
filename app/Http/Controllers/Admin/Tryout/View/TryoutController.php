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
    public function __invoke(Request $request)
    {
        $eventId = $request->get('event_id');
        $checkEventId = $this->tryoutService->findByEventId($eventId);
        $event = $this->eventService->show($eventId);
        if(!$checkEventId) return redirect()->back();
        return Inertia::render('admin/tryout', [
            'event_id' => $eventId,
            'event' => $event
        ]);
    }
}
