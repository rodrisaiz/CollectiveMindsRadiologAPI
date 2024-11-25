<?php

namespace App\V3\Application\UseCases\Subject;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;



class CreateSubject
{
    private SubjectRepositoryInterface $repository;

    private $eventService;
    private $webhookService;

    public function __construct(SubjectRepositoryInterface $repository, EventInterface $eventService, WebhookInterface $webhookService)
    {
        $this->repository = $repository;
        $this->eventService = $eventService;
        $this->webhookService = $webhookService;
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
               
        $value = $this->repository->findByEmail($email);

        $id = $value->getId(); 
        $subject->setId($id); 

        $this->eventService->dispatch($subject, 'Created subject');
        $this->webhookService->send('subjectV3', 'Created subject', $subject->getId());

        return $subject;

    }
}
