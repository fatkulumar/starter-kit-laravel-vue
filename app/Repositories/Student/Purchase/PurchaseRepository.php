<?php

namespace App\Repositories\Student\Purchase;

use App\Models\Purchase;
use App\Repositories\Repository;

class PurchaseRepository extends Repository implements PurchaseRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Purchase $model)
    {
        $this->model = $model;
    }
}
