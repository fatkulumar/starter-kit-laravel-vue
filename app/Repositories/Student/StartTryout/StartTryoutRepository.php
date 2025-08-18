<?php

namespace App\Repositories\Student\StartTryout;

use App\Models\StartTryout;
use App\Repositories\Repository;

class StartTryoutRepository extends Repository implements StartTryoutRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(StartTryout $model)
    {
        $this->model = $model;
    }
}
