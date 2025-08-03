<?php

namespace App\Services\Admin\Grade;

interface GradeServiceInterface
{
    public function getGrades(array $paginate): object;
}
