<?php

namespace App\V3\Application\UseCases\Subject;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Facades\Log;

class DeleteSubject
{
    private SubjectRepositoryInterface $repository;

    public function __construct(SubjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id)
    {
        $emptyuser = $this->repository->findById($id);
        if(empty($emptyuser)){
            return 1;
        }else{
            return $this->repository->delete($id);
        }
    }
}
