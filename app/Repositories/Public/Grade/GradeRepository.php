<?php

namespace App\Repositories\Public\Grade;

use App\Models\Grade;
use App\Repositories\Repository;

class GradeRepository extends Repository implements GradeRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Grade $model)
    {
        $this->model = $model;
    }
}
