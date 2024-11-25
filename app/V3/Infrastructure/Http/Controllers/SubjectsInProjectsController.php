<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\SubjectsInProjects\EnrollSubjectInProjectUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectsInProjectsController 
{
    private EnrollSubjectInProjectUseCase $useCase;

    public function __construct(EnrollSubjectInProjectUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function enroll(Request $request, $subjectId, $projectId)
    {
        try {
            $project = $this->useCase->execute($subjectId, $projectId);

            if ($project) {
                return response()->json([
                    'message' => 'Subject assigned to project successfully',
                    'project' => $project,
                ], 200);
            }

            return response()->json(['error' => 'Project or Subject not found'], 404);

        } catch (\Exception $e) {
            Log::info(['test' =>$e->getMessage()]);
            return response()->json([
                'error' => 'Error creating the assignment',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
