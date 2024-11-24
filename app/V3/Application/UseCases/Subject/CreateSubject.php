<?php

namespace App\V3\Application\UseCases\Subject;

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
    
        $subject = new Subject(
            null,
            $email,
            $firstName,
            $lastName,
            new \DateTime(),
            new \DateTime()
        );
        
        $this->repository->save($subject);
        
        Log::info(['Subject' =>  $subject]);
       
        $value = $this->repository->findByEmail($email);

        $id = $value->getId(); 
        $subject->setId($id); 
        
        return $subject;

    }
}
