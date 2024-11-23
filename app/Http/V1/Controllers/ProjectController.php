<?php

namespace App\Http\V1\Controllers;

use App\Models\Project;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\V2\Events\ProjectEvent;
use App\Models\Webhook;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::all();
    
        if(!$projects->isEmpty()){
        return response()->json([
            'data' => $projects
        ], 200);
        }else{
            return response()->json([
                'data' => 'There are not projects'
            ], 200);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::create($request->all());
            event(new ProjectEvent($project, 'Created project'));
            return response()->json([
                'data' => $project, 
                'message' => 'Project created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating project', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            return response()->json([
                'data' => $project
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Project not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving project', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showByName($name): JsonResponse
    {
        try {
            $project = Project::where('name', $name)->get();

            if(!$project->isEmpty()){
                return response()->json([
                    'data' => $project
                ], 200);

            }else{
                return response()->json([
                    'error' => 'Project not found'
                ], 404);

            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving project', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $project = Project::findOrFail($id);
            $project->update($request->all());

            event(new ProjectEvent($project, 'Updated project'));
            return response()->json([
                'data' => $project, 
                'message' => 'Project updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Project not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating project', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
