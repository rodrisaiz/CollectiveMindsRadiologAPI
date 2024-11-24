<?php

namespace App\V3\Application\UseCases;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class UpdateSubject
{
    private SubjectRepositoryInterface $repository;

    public function __construct(SubjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id, array $data): ?Subject
    {
        $existingSubject = $this->repository->findByEmail($data['email']);

        if ($existingSubject) {
            return $existingSubject; 
        }
        
        $subject = $this->repository->findById($id);
        if (isset($data['email'])) {
            $subject->setEmail($data['email']);
        }

        if (isset($data['first_name'])) {
            $subject->setFirstName($data['first_name']);
        }

        if (isset($data['last_name'])) {
            $subject->setLastName($data['last_name']);
        }

        $this->repository->save($subject);

        return $subject;
    }

}
