<?php

namespace App\V3\Domain\Repositories;

use App\V3\Domain\Entities\Project;

interface ProjectRepositoryInterface
{

    public function all(): array;
    public function findById(int $id): ?Project;
    public function findByName(string $Name): ?Project;
    public function save(Project $subject): void;
}
