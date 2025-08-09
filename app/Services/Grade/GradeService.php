<?php

namespace App\Services\Grade;

use App\Repositories\Grade\GradeRepository;
use App\Services\Grade\GradeServiceInterface;
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
    public function getGrades(): object
    {
        return $this->gradeRepository->all();
    }
}
