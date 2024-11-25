<?php

namespace App\V3\Infrastructure\Persistence;

use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\ProjectRepositoryInterface;
use App\Models\Project as EloquentProject;
use Illuminate\Support\Facades\Log;

class EloquentProjectRepository implements ProjectRepositoryInterface
{

    public function all(): array
    {
        $projectModels = EloquentProject::all();

        $latValue = $projectModels->map(fn ($model) => $this->toDomain($model))->toArray();

        return $latValue;
    }

    public function findById(int $id): ?Project
    {
        $projectModel = EloquentProject::find($id);
        return $projectModel ? $this->toDomain($projectModel) : null;
    }

    public function findByName(string $name): ?Project
    {
        $projectModel = EloquentProject::where('name', $name)->first();
        return $projectModel ? $this->toDomain($projectModel) : null;
    }

    public function save(Project $project): void
    {
        try {
            $EloquentProject = EloquentProject::updateOrCreate(
                ['name' => $project->getName()],
                [
                    'description' => $project->getDescription(),
                ]
            );
    
            $project->setWasRecentlyCreated($EloquentProject->wasRecentlyCreated);
    
        } catch (\Exception $e) {
            Log::error('Error saving project', [
                'name' => $project->getName(),
                'error' => $e->getMessage()
            ]);
    
            throw $e;
        }

    }

    private function toDomain(EloquentProject $model): Project
    {
        return new Project($model->id, $model->name, $model->description, $model->created_at, $model->updated_at);
    }
}
