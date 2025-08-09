<?php

namespace App\Services\Subject;

use App\Repositories\Subject\SubjectRepository;
use App\Services\Subject\SubjectServiceInterface;
use App\Services\Service;

class SubjectService extends Service implements SubjectServiceInterface
{
    private $subjectRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getSubjects(): object
    {
        return $this->subjectRepository->all();
    }
}
