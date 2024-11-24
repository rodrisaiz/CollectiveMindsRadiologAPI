<?php

namespace App\V3\Application\UseCases;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateSubject
{
    private SubjectRepositoryInterface $repository;

    public function __construct(SubjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $email, string $firstName, string $lastName): Subject
    {
        $existingSubject = $this->repository->findByEmail($email);
    
        if ($existingSubject) {
            return $existingSubject;
        }
    
        $subject = new Subject($email, $firstName, $lastName);
        $this->repository->save($subject);
        
        return $subject;
    }
}
