<?php

namespace App\Http\Controllers\Admin\Tryout\View;

use App\Http\Controllers\Controller;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $eventId = $request->get('event_id');
        return Inertia::render('admin/tryout', [
            'event_id' => $eventId
        ]);
    }
}
