<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\Subject\CreateSubject;
use App\V3\Application\UseCases\Subject\AllSubject;
use App\V3\Application\UseCases\Subject\FoundSubjectById;
use App\V3\Application\UseCases\Subject\FoundSubjectByEmail;
use App\V3\Application\UseCases\Subject\UpdateSubject;
use App\V3\Application\UseCases\Subject\DeleteSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;



class SubjectController
{
    private CreateSubject $createSubject;
    private AllSubject $AllSubject;
    private FoundSubjectById $FoundSubjectById;
    private FoundSubjectByEmail $FoundSubjectByEmail;
    private UpdateSubject $UpdateSubject;
    private DeleteSubject $deleteSubject;

    public function __construct(CreateSubject $createSubject, AllSubject $AllSubject, FoundSubjectById $FoundSubjectById, FoundSubjectByEmail $FoundSubjectByEmail, UpdateSubject $UpdateSubject, DeleteSubject $deleteSubject)
    {
        $this->createSubject = $createSubject;
        $this->AllSubject = $AllSubject;
        $this->FoundSubjectById = $FoundSubjectById;
        $this->FoundSubjectByEmail = $FoundSubjectByEmail;
        $this->UpdateSubject = $UpdateSubject;
        $this->deleteSubject = $deleteSubject;
    }

    public function index(): JsonResponse
    {   
        $allSubjects = $this->AllSubject->execute();

        if(!empty($allSubjects)){
            return response()->json([
                'data' => array_map(fn ($subject) => [
                    'id' => $subject->getId(),
                    'email' => $subject->getEmail(),
                    'first_name' => $subject->getFirstName(),
                    'last_name' => $subject->getLastName(),
                    "created_at" => $subject->getCreatedAt(),
                    "updated_at" => $subject->getUpdatedAt(),
                ], $allSubjects)
            ], 200);    
        }else{
            return response()->json([
                'message' => 'No subjects found',
            ], 200);    
        }
        
    }

    public function show($id): JsonResponse
    {   
        $foundedSubject = $this->FoundSubjectById->execute($id);

        if(!empty($foundedSubject)){
                return response()->json([
                'data' => [
                    'id' => $foundedSubject->getId(),
                    'email' => $foundedSubject->getEmail(),
                    'first_name' => $foundedSubject->getFirstName(),
                    'last_name' => $foundedSubject->getLastName(),
                    "created_at" => $foundedSubject->getCreatedAt(),
                    "updated_at" => $foundedSubject->getUpdatedAt(),
                ],
            ], 200);    
        }else{
            return response()->json([
                'message' => 'This subject does not exist',
            ], 200);    
        }
    }

    public function showByEmail($email): JsonResponse
    {   
        $foundedSubject = $this->FoundSubjectByEmail->execute($email);

        if(!empty($foundedSubject)){
            return response()->json([
                'data' => [
                        [
                           'id' => $foundedSubject->getId(),
                            'email' => $foundedSubject->getEmail(),
                            'first_name' => $foundedSubject->getFirstName(),
                            'last_name' => $foundedSubject->getLastName(),
                            "created_at" => $foundedSubject->getCreatedAt(),
                            "updated_at" => $foundedSubject->getUpdatedAt(),
                        ]
                    ]
        ], 200);    
        }else{
            return response()->json([
                'message' => 'This subject does not exist',
            ], 200);    
        }
    }
    
    public function store(Request $request): JsonResponse
    {    
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
            ]);
    
            $subject = $this->createSubject->execute(
                $data['email'],
                $data['first_name'],
                $data['last_name']
            );
    
            if ($subject->wasRecentlyCreated()) {
                return response()->json([
                    'data' => [
                        'email' => $subject->getEmail(),
                        'first_name' => $subject->getFirstName(),
                        'last_name' => $subject->getLastName(),
                        'created_at' => $subject->getCreatedAt()->format('Y-m-d\TH:i:s.u\Z'),
                        'updated_at' => $subject->getUpdatedAt()->format('Y-m-d\TH:i:s.u\Z'),
                        'id' => $subject->getId(),
                    ],
                    'message' => 'Subject created successfully'
                ], 201);
            }
    
            return response()->json([
                'error' => 'Error creating subject', 
                'message' => 'Email already exists'
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
            'email' => [
                'required',
                'email',
                Rule::unique('subjects')->ignore($id),
            ],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);
    
        try {
            $subject = $this->UpdateSubject->execute($id, $data);
        
            if ($subject->wasRecentlyCreated()) {
                return response()->json([
                    'data' => [
                        'id' => $subject->getId(),
                        'email' => $subject->getEmail(),
                        'first_name' => $subject->getFirstName(),
                        'last_name' => $subject->getLastName(),
                        'created_at' => $subject->getCreatedAt(),
                        'updated_at' => $subject->getUpdatedAt()
                    ],
                    'message' => 'Subject updated successfully'
                ], 201);
            }
    
            return response()->json([
                "error" => "Error updating subject",
                'message' => 'Email already exists'
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Error in update method', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to process the request'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {   
        $foundedSubject = $this->deleteSubject->execute($id);

        if(!empty($foundedSubject)){
            return response()->json([
                    'message' => 'Subject not found',
            ], 200);    
        }else{
            return response()->json([
                'message' => 'Subject deleted successfully',
            ], 200);    
        }
    }
    
}
