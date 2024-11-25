<?php

namespace App\V3\Infrastructure\Persistence;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\SubjectsInProjectsRepositoryInterface;
use App\Models\Project as EloquentProject;
use App\Models\Subject as EloquentSubject;

class EloquentSubjectsInProjectsRepository implements SubjectsInProjectsRepositoryInterface
{
    public function enroll(int $subjectId, int $projectId): ?Project
    {
        $subjectModel = EloquentSubject::find($subjectId);
        $projectModel = EloquentProject::find($projectId);

        if ($subjectModel && $projectModel) {
            $subjectModel->projects()->attach($projectModel);

            $subjectModel->save();
           
            return   $subjectModel;
        }

        return null;
    }
}
