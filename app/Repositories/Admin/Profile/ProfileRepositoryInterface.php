<?php

namespace App\Repositories\Admin\Profile;

interface ProfileRepositoryInterface
{
    public function updateOrCreate(array $where, array $data): object;
}
