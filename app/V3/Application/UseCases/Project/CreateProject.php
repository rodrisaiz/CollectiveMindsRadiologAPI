<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;

class CreateProject
{
    private ProjectRepositoryInterface $repository;

    private $eventService;
    private $webhookService;

    public function __construct(ProjectRepositoryInterface $repository, EventInterface $eventService, WebhookInterface $webhookService)
    {
        $this->repository = $repository;
        $this->eventService = $eventService;
        $this->webhookService = $webhookService;
    }

    public function execute(string $name, string $description): Project
    {
        $existingProject = $this->repository->findByName($name);
    
        if ($existingProject) {
            return $existingProject;
        }
    
        $Project = new Project(
            null,
            $name,
            $description,
            new \DateTime(),
            new \DateTime()
        );
        
        $this->repository->save($Project);
               
        $value = $this->repository->findByName($name);

        $id = $value->getId(); 
        $Project->setId($id); 

        $this->eventService->dispatch($Project, 'Created project');
        $this->webhookService->send('projectV3', 'Created project', $Project->getId());

        return $Project;

    }
}
