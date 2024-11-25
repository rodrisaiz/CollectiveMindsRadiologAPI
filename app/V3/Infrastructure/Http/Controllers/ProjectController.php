<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\Project\AllProject;
use App\V3\Application\UseCases\Project\CreateProject;
use App\V3\Application\UseCases\Project\FoundProjectById;
use App\V3\Application\UseCases\Project\FoundProjectByname;
use App\V3\Application\UseCases\Project\UpdateProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;



class ProjectController
{
    private CreateProject $createProject;
    private AllProject $allProject;
    private FoundProjectById $foundProjectById;
    private FoundProjectByname $foundProjectByName;
    private UpdateProject $updateProject;

    public function __construct(CreateProject $createProject, AllProject $allProject, FoundProjectById $foundProjectById, FoundProjectByname $foundProjectByName, UpdateProject $updateProject)
    {
        $this->createProject = $createProject;
        $this->allProject = $allProject;
        $this->foundProjectById = $foundProjectById;
        $this->foundProjectByName = $foundProjectByName;
        $this->updateProject = $updateProject;
    }

    public function index(): JsonResponse
    {   
        $allProjects = $this->allProject->execute();

        if(!empty($allProjects)){
            return response()->json([
                'data' => array_map(fn ($project) => [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'description' => $project->getDescription(),
                    "created_at" => $project->getCreatedAt(),
                    "updated_at" => $project->getUpdatedAt(),
                ], $allProjects)
            ], 200);    
        }else{
            return response()->json([
                'message' => 'No projects found',
            ], 200);    
        }
        
    }

    public function show($id): JsonResponse
    {   
        $foundedproject = $this->foundProjectById->execute($id);

        if(!empty($foundedproject)){
                return response()->json([
                'data' => [
                    'id' => $foundedproject->getId(),
                    'name' => $foundedproject->getname(),
                    'description' => $foundedproject->getDescription(),
                    "created_at" => $foundedproject->getCreatedAt(),
                    "updated_at" => $foundedproject->getUpdatedAt(),
                ],
            ], 200);    
        }else{
            return response()->json([
                'message' => 'This project does not exist',
            ], 200);    
        }
    }

    public function showByname($name): JsonResponse
    {   
        $foundedproject = $this->foundProjectByName->execute($name);

        if(!empty($foundedproject)){
            return response()->json([
                'data' => [
                        [
                           'id' => $foundedproject->getId(),
                            'name' => $foundedproject->getname(),
                            'description' => $foundedproject->getDescription(),
                            "created_at" => $foundedproject->getCreatedAt(),
                            "updated_at" => $foundedproject->getUpdatedAt(),
                        ]
                    ]
        ], 200);    
        }else{
            return response()->json([
                'message' => 'This project does not exist',
            ], 200);    
        }
    }
    
    public function store(Request $request): JsonResponse
    {    
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
    
            $project = $this->createProject->execute(
                $data['name'],
                $data['description'],
            );
    
            if ($project->wasRecentlyCreated()) {
                return response()->json([
                    'data' => [
                        'name' => $project->getname(),
                        'description' => $project->getDescription(),
                        'created_at' => $project->getCreatedAt()->format('Y-m-d\TH:i:s.u\Z'),
                        'updated_at' => $project->getUpdatedAt()->format('Y-m-d\TH:i:s.u\Z'),
                        'id' => $project->getId(),
                    ],
                    'message' => 'project created successfully'
                ], 201);
            }
    
            return response()->json([
                'error' => 'Error creating project', 
                'message' => 'Name already exists'
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $e->errors() 
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error', 
                'message' => $e->getMessage() 
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {  
        $data = $request->validate([
            'name' => [
                'required',
                'string',
            ],
            'description' => 'nullable|string',
        ]);
        try {
            $project = $this->updateProject->execute($id, $data);
            if ($project->wasRecentlyCreated()) {
                return response()->json([
                    'data' => [
                        'id' => $project->getId(),
                        'name' => $project->getname(),
                        'description' => $project->getDescription(),
                        'created_at' => $project->getCreatedAt(),
                        'updated_at' => $project->getUpdatedAt()
                    ],
                    'message' => 'Project updated successfully'
                ], 201);
            }
    
            return response()->json([
                "error" => "Error updating project",
                'message' => 'Name already exists'
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error in update method', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to process the request'], 500);
        }
    }
}
