<?php

namespace App\Repositories\Subject;

use App\Models\Subject;
use App\Repositories\Repository;

class SubjectRepository extends Repository implements SubjectRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Subject $model)
    {
        $this->model = $model;
    }
}
