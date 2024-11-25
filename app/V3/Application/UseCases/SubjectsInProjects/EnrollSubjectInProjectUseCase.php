<?php

namespace App\V3\Application\UseCases\SubjectsInProjects;

use App\V3\Domain\Repositories\SubjectsInProjectsRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EnrollSubjectInProjectUseCase
{
    private SubjectsInProjectsRepositoryInterface $repository;

    public function __construct(SubjectsInProjectsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $subjectId, int $projectId)
    {
        $value = $this->repository->enroll($subjectId, $projectId);

        return $value;
    }
}
