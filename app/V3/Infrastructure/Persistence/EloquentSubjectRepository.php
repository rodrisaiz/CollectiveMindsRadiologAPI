<?php

namespace App\V3\Infrastructure\Persistence;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Entities\Project;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use App\Models\Subject as EloquentSubject;
use Illuminate\Support\Facades\Log;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{

    public function all(): array
    {
        $subjectModels = EloquentSubject::with('projects')->get(); // Precargar proyectos
        $subjects = [];

        foreach ($subjectModels as $subjectModel) {
            $subject = new Subject(
                $subjectModel->id,
                $subjectModel->email,
                $subjectModel->first_name,
                $subjectModel->last_name,
                $subjectModel->created_at,
                $subjectModel->updated_at
            );

            if ($subjectModel->projects) {
                $projects = $subjectModel->projects->map(function ($projectModel) {
                    return new Project(
                        $projectModel->id,
                        $projectModel->name,
                        $projectModel->description,
                        $projectModel->created_at,
                        $projectModel->updated_at
                    );
                })->toArray();

                $subject->setProjects($projects);
            }

            $subjects[] = $subject;
        }

        return $subjects;
    }

    public function findById(int $id): ?Subject
    {
        $subjectModel = EloquentSubject::with('projects')->find($id); 
    
        if (!$subjectModel) {
            return null; 
        }
    
        $subject = new Subject(
            $subjectModel->id,
            $subjectModel->email,
            $subjectModel->first_name,
            $subjectModel->last_name,
            $subjectModel->created_at,
            $subjectModel->updated_at
        );
    
        if ($subjectModel->projects) {
            $projects = $subjectModel->projects->map(function ($projectModel) {
                return new Project(
                    $projectModel->id,
                    $projectModel->name,
                    $projectModel->description,
                    $projectModel->created_at,
                    $projectModel->updated_at
                );
            })->toArray();
    
            $subject->setProjects($projects);
        }
    
        return $subject;
    }
    

    public function findByEmail(string $email): ?Subject
    {   $subjectModel = EloquentSubject::where('email', $email)->with('projects')->first();
        if (!$subjectModel) {
            Log::warning("No se encontró subject con email: {$email}");
            return null; 
        }
    
        
        $subject = new Subject(
            $subjectModel->id,
            $subjectModel->email,
            $subjectModel->first_name,
            $subjectModel->last_name,
            $subjectModel->created_at,
            $subjectModel->updated_at
        );
    
        if ($subjectModel->projects) {
            $projects = $subjectModel->projects->map(function ($projectModel) {
                return new Project(
                    $projectModel->id,
                    $projectModel->name,
                    $projectModel->description,
                    $projectModel->created_at,
                    $projectModel->updated_at
                );
            })->toArray();
    
            $subject->setProjects($projects);
        }
    
        return $this->toDomain($subjectModel);
    }

    public function save(Subject $subject): void
    {
        try {
            $eloquentSubject = EloquentSubject::updateOrCreate(
                ['email' => $subject->getEmail()],
                [
                    'first_name' => $subject->getFirstName(),
                    'last_name' => $subject->getLastName(),
                ]
            );
    
            $subject->setWasRecentlyCreated($eloquentSubject->wasRecentlyCreated);
    
        } catch (\Exception $e) {
            Log::error('Error saving Subject', [
                'email' => $subject->getEmail(),
                'error' => $e->getMessage()
            ]);
    
            throw $e;
        }

    }

    public function delete($id): void
    {
        EloquentSubject::find($id)->delete();
    }

    private function toDomain(EloquentSubject $model): Subject
    {
        return new Subject($model->id, $model->email, $model->first_name, $model->last_name,$model->created_at, $model->updated_at);
    }
}
