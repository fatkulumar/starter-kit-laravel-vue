<?php

namespace App\Repositories\Admin\Profile;

use App\Models\Profile;
use App\Repositories\Repository;

class ProfileRepository extends Repository implements ProfileRepositoryInterface
{
    protected $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Profile $model)
    {
        $this->model = $model;
    }

    /** 
     * update or create by id 
    */
    public function updateOrCreate(array $where, array $data): object
    {
        return $this->model::updateOrCreate($where, $data);
    }
}
