<?php

namespace App\Repositories\Student\Answer;

use App\Models\Answer;
use App\Repositories\Repository;

class AnswerRepository extends Repository implements AnswerRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Answer $model)
    {
        $this->model = $model;
    }
}
