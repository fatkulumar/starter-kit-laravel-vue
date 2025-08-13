<?php

namespace App\Http\Controllers\Admin\Subtest\Api;

use App\DataTransferObjects\SubtestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subtest\SubtestDeleteAllRequest;
use App\Http\Requests\Admin\Subtest\SubtestStoreRequest;
use App\Http\Requests\Admin\Subtest\SubtestUpdateRequest;
use App\Services\Admin\Subtest\SubtestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubtestController extends Controller
{
    private $subtestService;
    /**
     * Create a new class instance.
     */
    public function __construct(SubtestService $subtestService)
    {
        $this->subtestService = $subtestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $tryoutId = $request->query('tryout_id');
        $payload = [
            'search' => $search,
            'cacheKey' => 'subtests_admin:search=' . ($search ?: 'all') . $tryoutId . ':page_subtests_admin=' . $page . '_' . $tryoutId,
            'paginate' => 10,
            'minutes' => 10,
            'tryout_id' => $tryoutId
        ];
        $result = $this->subtestService->getSubtestByTryoutId($payload);
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
    public function store(SubtestStoreRequest $request): JsonResponse
    {
        $dto = SubtestDTO::fromArray($request->validated());
        $result = $this->subtestService->store($dto);
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
    public function update(SubtestUpdateRequest $request, string $id): JsonResponse
    {
        $dto = SubtestDTO::fromArray($request->validated());
        $result = $this->subtestService->update($dto);
        $this->setResult($result)->setStatus(true)->setMessage('Success Save Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->subtestService->delete($id);
        $this->setResult($id)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Remove the many data.
     */
    public function deleteAll(SubtestDeleteAllRequest $request): JsonResponse
    {
        $dataValidate = $request->validated();
        $result = $this->subtestService->destroy($dataValidate['ids']);
        $this->setResult($result)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
