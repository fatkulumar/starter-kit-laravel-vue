<?php

namespace App\Repositories\Admin\Question;

use App\Models\Question;
use App\Repositories\Admin\Question\QuestionRepositoryInterface;
use App\Repositories\Repository;

class QuestionRepository extends Repository implements QuestionRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Question $model)
    {
        $this->model = $model;
    }

    /**
     * Get data by subtest_id.
     */
    public function getQuestionBySubtestId(string $subtestId): object
    {
        return $this->model::where('subtest_id', $subtestId)->get();
    }
}
