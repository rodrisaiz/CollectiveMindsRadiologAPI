<?php

namespace App\V3\Domain\Repositories;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Entities\Project;

interface SubjectsInProjectsRepositoryInterface
{
    public function enroll(int $subjectId, int $projectId): ?Project;
}
