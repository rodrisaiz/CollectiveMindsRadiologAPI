<?php

namespace App\V3\Infrastructure\Http\Controllers;

use App\V3\Application\UseCases\CreateSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController
{
    private CreateSubject $createSubject;

    public function __construct(CreateSubject $createSubject)
    {
        $this->createSubject = $createSubject;
    }

    public function store(Request $request): JsonResponse
    {
        Log::info('store');
    
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
