<?php

namespace App\Http\Controllers\Student\Answer\Api;

use App\DataTransferObjects\AnswerDTO;
use App\DataTransferObjects\FinishExamDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Answer\AnswerStoreRequest;
use App\Http\Requests\Student\FinishExamRequest;
use App\Services\Student\Answer\AnswerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    private $answerService;
    /**
     * Create a new class instance.
     */
    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $subtestId = $request->query('subtest_id');
        $userId = Auth::user()->id;
        $payload = [
            'search' => $search,
            'cacheKey' => 'answer_student:search=' . ($search ?: 'all') . ':answer_student=' . $page . $subtestId . $userId,
            'minutes' => 10,
            'subtest_id' => $subtestId
        ];
        $result = $this->answerService->getAnswer($payload);
        $this->setResult($result)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnswerStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $dto = AnswerDTO::fromArray($data);
        $result = $this->answerService->store($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Submit finish exam.
     */
    public function finishExam(FinishExamRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $dto = FinishExamDTO::fromArray($data);
        $result = $this->answerService->finishExam($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
