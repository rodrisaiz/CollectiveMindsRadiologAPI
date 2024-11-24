<?php

namespace App\V3\Domain\Repositories;

use App\V3\Domain\Entities\Subject;

interface SubjectRepositoryInterface
{

    public function all(): array;
    public function findById(int $id): ?Subject;
    public function findByEmail(string $email): ?Subject;
    public function save(Subject $subject): void;
    public function delete(int $id): void;
}
