<?php

namespace App\Http\Controllers\Admin\Question\Api;

use App\DataTransferObjects\QuestionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Question\QuestionStoreRequest;
use App\Http\Requests\Admin\Question\QuestionUpdateRequest;
use App\Services\Admin\Question\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $subtestId = $request->query('subtest_id');
        $result = $this->questionService->getQuestionBySubtestId($subtestId);
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
    public function store(QuestionStoreRequest $request): JsonResponse
    {
        $dto = QuestionDTO::fromArray($request->validated());
        $result = $this->questionService->store($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Berhasil Tambah Soal')->setCode(JsonResponse::HTTP_OK);
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
    public function update(QuestionUpdateRequest $request, string $id): JsonResponse
    {
        $dto = QuestionDTO::fromArray($request->validated());
        $result = $this->questionService->update($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Berhasil Update Soal')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
