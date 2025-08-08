<?php

namespace App\Services\Admin\Question;

use App\DataTransferObjects\QuestionDTO;
use App\Repositories\Admin\Question\QuestionRepository;
use App\Repositories\Admin\Subtest\SubtestRepository;
use App\Services\Admin\Question\QuestionServiceInterface;
use App\Services\Service;

class QuestionService extends Service implements QuestionServiceInterface
{
    private $subtestRepository, $questionRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SubtestRepository $subtestRepository, QuestionRepository $questionRepository)
    {
        $this->subtestRepository = $subtestRepository;
        $this->questionRepository = $questionRepository;
    }

    /**
     * Get data by subtest_code.
     */
    public function getSubtestBySubtestCode(string $subtestCode): object
    {
        return $this->subtestRepository->getSubtestBySubtestCode($subtestCode);
    }

    /**
     * Get data by subtest_id.
     */
    public function getQuestionBySubtestId(string $subtestId): object
    {
        return $this->questionRepository->getQuestionBySubtestId($subtestId);
    }

     /**
     * Create data.
     */
    public function store(QuestionDTO $dto): object
    {
        $data = [
            'subtest_id' => $dto->subtestId,
            'subject_id' => $dto->subjectId,
            'option_a' => $dto->optionA,
            'option_b' => $dto->optionB,
            'option_c' => $dto->optionC,
            'option_d' => $dto->optionD,
            'option_e' => $dto->optionE,
            'correct_answer' => $dto->correctAnswer,
            'explanation' => $dto->explanation,
        ];
        $question = $this->questionRepository->store($data);
        return $this->questionRepository->show($question->id);
    }

     /**
     * Update data.
     */
    public function update(QuestionDTO $dto): object
    {
        $data = [
            'subtest_id' => $dto->subtestId,
            'subject_id' => $dto->subjectId,
            'option_a' => $dto->optionA,
            'option_b' => $dto->optionB,
            'option_c' => $dto->optionC,
            'option_d' => $dto->optionD,
            'option_e' => $dto->optionE,
            'correct_answer' => $dto->correctAnswer,
            'explanation' => $dto->explanation,
        ];
        $this->questionRepository->update($dto->id, $data);
        return $this->questionRepository->show($dto->id);
    }
}
