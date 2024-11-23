<?php

namespace App\Http\V1\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;
use App\Http\V2\Events\SubjectEvent;



class SubjectsInProjectsController extends Controller
{
    public function enroll(Request $request, $subjectId, $projectId)
    {
        try {
            $subject = Subject::findOrFail($subjectId);
            $project = Project::findOrFail($projectId);

            $subject->projects()->attach($project);
            $subject->load('projects');
            event(new SubjectEvent($subject, 'Subject enroll in a project'));
            return response()->json([
                'message' => 'Subject assigned to project successfully',
                'subject' => $subject,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating the assigment', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
