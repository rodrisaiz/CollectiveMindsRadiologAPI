<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;

class UpdateProject
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

    public function execute(int $id, array $data): ?Project
    {
        $existingProject = $this->repository->findByName($data['name']);

        if ($existingProject) {
            return $existingProject; 
        }
        
        $Project = $this->repository->findById($id);
        if (isset($data['name'])) {
            $Project->setName($data['name']);
        }

        if (isset($data['description'])) {
            $Project->setDescription($data['description']);
        }

        $this->repository->save($Project);

        $this->eventService->dispatch($Project, 'Updated project');
        $this->webhookService->send('ProjectV3', 'Updated project', $Project->getId());

        return $Project;
    }

}
