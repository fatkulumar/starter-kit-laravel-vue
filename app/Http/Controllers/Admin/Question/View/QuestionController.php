<?php

namespace App\Http\Controllers\Admin\Question\View;

use App\Http\Controllers\Controller;
use App\Services\Admin\Question\QuestionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    private $questionService;
    /**
     * Create a new class instance.
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $subtestCode = $request->query('subtest_code');
        $subtest = $this->questionService->getSubtestBySubtestCode($subtestCode);
        if (!$subtest) return redirect()->back();
        return Inertia::render('admin/question', [
            'subtest' => $subtest
        ]);
    }
}
