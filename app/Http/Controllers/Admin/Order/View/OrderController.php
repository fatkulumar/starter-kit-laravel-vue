<?php

namespace App\Http\Controllers\Admin\Order\View;

use App\Http\Controllers\Controller;
use App\Services\Admin\Order\OrderService;
use App\Services\Admin\Tryout\TryoutService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    private $tryoutService, $orderService;

    /**
     * Create new a class instance.
     */
    public function __construct(TryoutService $tryoutService, OrderService $orderService)
    {
        $this->tryoutService = $tryoutService;
        $this->orderService = $orderService;
    }

    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        return Inertia::render('admin/order');
    }

    /**
     * List users ordered.
     */
    public function hasOrderTryout(Request $request)
    {
        $tryoutCode = $request->query('tryout_code');
        $tryout = $this->tryoutService->findByTryoutCode($tryoutCode);
        if (!$tryout) return redirect()->back();
        return Inertia::render('admin/hasOrderTryout', [
            'tryout' => $tryout,
        ]);
    }
}
