<?php

namespace App\V3\Application\UseCases\Subject;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;
use Exception;


class UpdateSubject
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

    public function execute(int $id, array $data): ?Subject
    {   
       $existingSubject = $this->repository->findByEmail($data['email']);
        if (!is_null($existingSubject)) {
          return $existingSubject; 
        }
        
        $subject = $this->repository->findById($id);

        if (is_null($subject)) {
            throw new Exception("Subject with ID {$id} not found.");
        }


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

        $this->eventService->dispatch($subject, 'Updated subject');
        $this->webhookService->send('subjectV3', 'Updated subject', $subject->getId());

        return $subject;
    }

}
