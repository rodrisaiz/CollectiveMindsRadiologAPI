<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class UpdateProject
{
    private ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

        return $Project;
    }

}
