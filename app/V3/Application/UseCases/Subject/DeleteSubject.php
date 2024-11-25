<?php

namespace App\V3\Application\UseCases\Subject;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\V3\Domain\Contracts\EventInterface;
use App\V3\Domain\Contracts\WebhookInterface;

class DeleteSubject
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

    public function execute(int $id)
    {
        $emptyuser = $this->repository->findById($id);
        if(empty($emptyuser)){
            return 1;
        }else{
            $this->eventService->dispatch($emptyuser, 'Deleted subject');
            $this->webhookService->send('subjectV3', 'Deleted subject', $emptyuser->getId());
            return $this->repository->delete($id);
        }
    }
}
