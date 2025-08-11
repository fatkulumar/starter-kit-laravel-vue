<?php

namespace App\Services\Public\Grade;

use App\Repositories\Public\Grade\GradeRepository;
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
