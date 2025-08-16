<?php

namespace App\Http\Controllers\Student\Tryout\View;

use App\Http\Controllers\Controller;
use App\Services\Student\Event\EventService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TryoutController extends Controller
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
    public function __invoke(Request $request, string $eventCode)
    {   
        $event = $this->eventService->findByEventCode($eventCode);
        if (!$event) redirect()->back();
        return Inertia::render('student/Tryout', [
            'event' => $event
        ]);
    }
}
