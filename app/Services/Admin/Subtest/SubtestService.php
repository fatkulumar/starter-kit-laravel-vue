<?php

namespace App\Services\Admin\Subtest;

use App\DataTransferObjects\SubtestDTO;
use App\Repositories\Admin\Subtest\SubtestRepository;
use App\Services\Service;
use Illuminate\Support\Facades\Cache;

class SubtestService extends Service implements SubtestServiceInterface
{
    private $subtestRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SubtestRepository $subtestRepository)
    {
        $this->subtestRepository = $subtestRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getSubtestByTryoutId(array $payload): object
    {
        return $this->subtestRepository->getSubtestByTryoutId($payload);
    }

    /**
     * Create data.
     */
    public function store(SubtestDTO $dto): object
    {
        $data = [
            'title' => $dto->title,
            'subject_id' => $dto->subject_id,
            'tryout_id' => $dto->tryout_id,
            'amount_question' => $dto->amount_question,
            'amount_minutes' => $dto->amount_minutes,
        ];
        $this->subtestRepository->store($data);
        Cache::flush();
        return $this->subtestRepository->getSubtestWhereTryoutId($dto->tryout_id);
    }


    /**
     * update data.
     */
    public function update(SubtestDTO $dto): object
    {
        $subtestRepository = $this->subtestRepository->show($dto->id);

        $updateData = [];

        if ($dto->title !== null) $updateData['title'] = $dto->title;
        if ($dto->tryout_id !== null) $updateData['tryout_id'] = $dto->tryout_id;
        if ($dto->subject_id !== null) $updateData['subject_id'] = $dto->subject_id;
        if ($dto->amount_question !== null) $updateData['amount_question'] = $dto->amount_question;
        if ($dto->amount_minutes !== null) $updateData['amount_minutes'] = $dto->amount_minutes;

        $subtestRepository->fill($updateData);
        $subtestRepository->save();

        Cache::flush();
        return $this->subtestRepository->getSubtestWhereTryoutId($dto->tryout_id);
    }


    /**
     * delete one data.
     */
    public function delete(string $id): bool
    {
        $data = $this->subtestRepository->show($id);
        Cache::flush();
        return $data->delete($id);
    }

    /**
     * delete many data.
     */
    public function destroy(array $ids): array
    {
        foreach ($ids as $id) {
            $data = $this->subtestRepository->show($id);
            $data->delete($id);
        }
        Cache::flush();
        return $ids;
    }

    /**
     * find.
     */
    public function show(string $id): object
    {
        return $this->subtestRepository->show($id);
    }
}
