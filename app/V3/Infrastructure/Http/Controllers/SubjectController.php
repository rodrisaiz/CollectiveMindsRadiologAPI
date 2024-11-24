<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\CreateSubject;
use App\V3\Application\UseCases\AllSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController
{
    private CreateSubject $createSubject;
    private AllSubject $AllSubject;

    public function __construct(CreateSubject $createSubject, AllSubject $AllSubject )
    {
        $this->createSubject = $createSubject;
        $this->AllSubject = $AllSubject;
    }

    public function index(): JsonResponse
    {   
        $allSubjects = $this->AllSubject->execute();

        return response()->json([
            'data' => array_map(fn ($subject) => [
                'id' => $subject->getId(),
                'email' => $subject->getEmail(),
                'first_name' => $subject->getFirstName(),
                'last_name' => $subject->getLastName(),
            ], $allSubjects),
            'message' => count($allSubjects) > 0 ? 'These are all the subjects' : 'No subjects found',
        ], 200);

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
    
}
