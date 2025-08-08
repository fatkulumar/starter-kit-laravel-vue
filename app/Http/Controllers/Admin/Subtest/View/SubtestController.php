<?php

namespace App\Http\Controllers\Admin\Subtest\View;

use App\Http\Controllers\Controller;
use App\Services\Admin\Subtest\SubtestService;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubtestController extends Controller
{
    private $tryoutService, $subtestService;

    /**
     * Create new a class instance.
     */
    public function __construct(TryoutService $tryoutService, SubtestService $subtestService)
    {
        $this->tryoutService = $tryoutService;
        $this->subtestService = $subtestService;
    }
    
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $tryoutCode = $request->query('tryout_code');
        $tryout = $this->tryoutService->findByTryoutCode($tryoutCode);
        if (!$tryout) return redirect()->back();
        return Inertia::render('admin/subtest', [
            'tryout' => $tryout
        ]);
    }
}
