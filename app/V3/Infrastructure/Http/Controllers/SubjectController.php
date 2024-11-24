<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\CreateSubject;
use App\V3\Application\UseCases\AllSubject;
use App\V3\Application\UseCases\FoundSubjectById;
use App\V3\Application\UseCases\FoundSubjectByEmail;
use App\V3\Application\UseCases\UpdateSubject;
use App\V3\Application\UseCases\DeleteSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                'id' => $foundedSubject->getId(),
                'email' => $foundedSubject->getEmail(),
                'first_name' => $foundedSubject->getFirstName(),
                'last_name' => $foundedSubject->getLastName(),
            ],
        ], 200);    
        }else{
            return response()->json([
                'message' => 'This subject does not exist',
            ], 200);    
        }
    }
    
    public function store(Request $request): JsonResponse
    {    
        $data = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);
    
        try {
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
                    ],
                    'message' => 'Subject was created successfully '
                ], 201);
            }
    
            return response()->json([
                'message' => 'Subject already exists'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in store method', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to process the request'], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'email' => 'sometimes|email',
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
        ]);
    
        try {
            $subject = $this->UpdateSubject->execute($id, $data);
    
            Log::info('Subject updated', ['subject' => $subject]);
    
            if ($subject->wasRecentlyCreated()) {
                return response()->json([
                    'data' => [
                        'email' => $subject->getEmail(),
                        'first_name' => $subject->getFirstName(),
                        'last_name' => $subject->getLastName(),
                    ],
                    'message' => 'Subject was updated successfully '
                ], 201);
            }
    
            return response()->json([
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
        Log::info(['foundedSubject' => $foundedSubject]);
        if(!empty($foundedSubject)){
            return response()->json([
                    'message' => 'This subject does not exist',
            ], 200);    
        }else{
            return response()->json([
                'message' => 'Subject was deleted successfully',
            ], 200);    
        }
    }

    


    
}
