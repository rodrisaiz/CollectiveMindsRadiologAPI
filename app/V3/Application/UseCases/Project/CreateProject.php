<?php

namespace App\V3\Application\UseCases\Project;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CreateProject
{
    private ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

        return $Project;

    }
}
