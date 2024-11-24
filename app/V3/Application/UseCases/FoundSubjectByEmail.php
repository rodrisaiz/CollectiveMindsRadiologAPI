<?php

namespace App\V3\Application\UseCases;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FoundSubjectByEmail
{
    private SubjectRepositoryInterface $repository;

    public function __construct(SubjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $email): Subject
    {
        return $this->repository->findByEmail($email);
    }
}
