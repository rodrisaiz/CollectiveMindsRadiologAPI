<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FoundProjectByName
{
    private ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $name): ?Project
    {
        return $this->repository->findByName($name);
    }
}
