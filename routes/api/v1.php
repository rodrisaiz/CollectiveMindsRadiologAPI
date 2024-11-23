<?php

use App\Http\V1\Controllers\ProjectController;
use App\Http\V1\Controllers\SubjectController;
use App\Http\V1\Controllers\SubjectsInProjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

//Create newUser -> Only for propouse of demostration
Route::post('Cr34t3/n3wUs3r', function () {
    $user = User::create([
        'name' => 'CollectiveMindsClient',
        'email' => 'rodrisaiz@icloud.com',
        'password' => bcrypt('CollectiveMinds2024'), 
    ]);

    $token = $user->createToken('CollectiveMindsClient')->plainTextToken;

    return response()->json(['token' => $token], 201);
});


//Subjects
Route::resource('subject', SubjectController::class)->only(
    'index', 'store', 'show', 'update', 'destroy'
)->middleware('auth:sanctum');

Route::get('subject/email/{email}', [SubjectController::class, 'showByEmail'])->middleware('auth:sanctum');

//Projects
Route::resource('project', ProjectController::class)->only([
    'index', 'store', 'show', 'update'
])->middleware('auth:sanctum');

Route::get('project/name/{name}', [ProjectController::class, 'showByName'])->middleware('auth:sanctum');

//Assigment of subjects to projects
Route::post('enroll/{subjectId}/{projectId}', [SubjectsInProjectsController::class, 'enroll'])->middleware('auth:sanctum');
