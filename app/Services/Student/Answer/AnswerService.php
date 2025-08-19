<?php

namespace App\Services\Student\Answer;

use App\DataTransferObjects\AnswerDTO;
use App\Repositories\Student\Answer\AnswerRepository;
use App\Services\Service;

class AnswerService extends Service implements AnswerServiceInterface
{
    private $answerRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * List data answer.
     */
    public function getAnswer(array $payload): object
    {
        return $this->answerRepository->all($payload);
    }

    /**
     * Store data.
     */
    public function store(AnswerDTO $dto): object
    {
        $where = [
            'subtest_id' => $dto->subtest_id,
            'user_id' => $dto->user_id,
            'question_id' => $dto->question_id,
        ];

        $data = [
            'answer' => $dto->answer,
        ];

        return $this->answerRepository->updateOrCreate($where, $data);
    }
}
