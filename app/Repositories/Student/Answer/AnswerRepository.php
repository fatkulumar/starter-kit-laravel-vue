<?php

namespace App\Repositories\Student\Answer;

use App\Models\Tryout;
use App\Repositories\Repository;

class AnswerRepository extends Repository implements AnswerRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Tryout $model)
    {
        $this->model = $model;
    }
}
