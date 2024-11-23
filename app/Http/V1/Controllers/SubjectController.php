<?php

namespace App\Http\V1\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index(): JsonResponse
    {
        $subjects = Subject::with('projects')->get();

        if(!$subjects->isEmpty()){
        return response()->json([
            'data' => $subjects
        ], 200);
        }else{
            return response()->json([
                'data' => 'There are not subjects'
            ], 200);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $subject = Subject::create($request->all());
            return response()->json([
                'data' => $subject, 
                'message' => 'Subject created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->load('projects');
            return response()->json([
                'data' => $subject
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Subject not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showByEmail($email): JsonResponse
    {
        try {
            $subject = Subject::where('email', $email)->get();
            $subject->load('projects');
            if(!$subject->isEmpty()){
                return response()->json([
                    'data' => $subject
                ], 200);

            }else{
                return response()->json([
                    'error' => 'Subject not found'
                ], 404);

            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required|email',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation Error', 
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $subject = Subject::findOrFail($id);
            $subject->update($request->all());
            return response()->json([
                'data' => $subject, 
                'message' => 'Subject updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Subject not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();
            return response()->json([
                'message' => 'Subject deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Subject not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error deleting subject', 
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
