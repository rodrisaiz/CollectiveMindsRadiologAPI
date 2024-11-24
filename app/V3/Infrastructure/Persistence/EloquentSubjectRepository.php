<?php

namespace App\V3\Infrastructure\Persistence;

use App\V3\Domain\Entities\Subject;
use App\V3\Domain\Repositories\SubjectRepositoryInterface;
use App\Models\Subject as EloquentSubject;
use Illuminate\Support\Facades\Log;

class EloquentSubjectRepository implements SubjectRepositoryInterface
{

    public function all(): array
    {
        $subjectModels = EloquentSubject::all();

        $latValue = $subjectModels->map(fn ($model) => $this->toDomain($model))->toArray();

        return $latValue;
    }

    public function findById(int $id): ?Subject
    {
        $subjectModel = EloquentSubject::find($id);
        return $subjectModel ? $this->toDomain($subjectModel) : null;
    }

    public function findByEmail(string $email): ?Subject
    {
        $subjectModel = EloquentSubject::where('email', $email)->first();
        return $subjectModel ? $this->toDomain($subjectModel) : null;
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
