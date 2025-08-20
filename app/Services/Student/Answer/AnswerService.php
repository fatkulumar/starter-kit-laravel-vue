<?php

namespace App\Services\Student\Answer;

use App\DataTransferObjects\AnswerDTO;
use App\DataTransferObjects\FinishExamDTO;
use App\Repositories\Student\Answer\AnswerRepository;
use App\Services\Service;
use Illuminate\Support\Facades\Cache;

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
        return $this->answerRepository->getBySubtestId($payload);
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

        Cache::flush();
        return $this->answerRepository->updateOrCreate($where, $data);
    }

    public function finishExam(FinishExamDTO $dto): object
    {
        foreach ($dto->answers as $item) {

            $where = [
                'subtest_id' => $item['subtest_id'],
                'question_id' => $item['question_id'],
                'user_id' => $dto->user_id,
            ];

            $data = [
                'answer' => $item['answer'],
            ];
            Cache::flush();
            $this->answerRepository->updateOrCreate($where, $data);
        }
        return (object) [
            'code' => 200,
            'message' => 'Exam Finished'
        ];
    }
}
