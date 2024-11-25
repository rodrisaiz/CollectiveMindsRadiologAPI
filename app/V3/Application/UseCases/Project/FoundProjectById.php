<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class FoundProjectById
{
    private ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): ?Project
    {
        return $this->repository->findById($id);
    }
}
