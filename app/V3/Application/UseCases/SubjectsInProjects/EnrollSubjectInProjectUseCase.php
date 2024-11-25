<?php

namespace App\V3\Application\UseCases\SubjectsInProjects;

use App\V3\Domain\Repositories\SubjectsInProjectsRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;

class EnrollSubjectInProjectUseCase
{
    private SubjectsInProjectsRepositoryInterface $repository;

    private $eventService;
    private $webhookService;

    public function __construct(SubjectsInProjectsRepositoryInterface $repository, EventInterface $eventService, WebhookInterface $webhookService)
    {
        $this->repository = $repository;
        $this->eventService = $eventService;
        $this->webhookService = $webhookService;
    }

    public function execute(int $subjectId, int $projectId)
    {
        $value = $this->repository->enroll($subjectId, $projectId);

        $this->eventService->dispatch($value, 'Subject enrolled in a project');
        $this->webhookService->send('subjectV3', 'Subject enrolled in a project', $subjectId);

        return $value;
    }
}
