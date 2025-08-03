<?php

namespace App\Services\Admin\Grade;

use App\Repositories\Admin\Grade\GradeRepository;
use App\Services\Admin\Grade\GradeServiceInterface;
use App\Services\Service;

class GradeService extends Service implements GradeServiceInterface
{
    private $gradeRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getGrades(array $payload): object
    {
        return $this->gradeRepository->all($payload);
    }
}
