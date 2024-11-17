<?php

namespace App\Http\V1\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $projects = Project::all();
            return response()->json([
                'data' => $projects
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving projects', 
                'message' => $e->getMessage()
            ], 500);
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
