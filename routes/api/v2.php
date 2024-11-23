<?php

use App\Http\V2\Controllers\ProjectController;
use App\Http\V2\Controllers\SubjectController;
use App\Http\V2\Controllers\SubjectsInProjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\V2\Controllers\WebhookController;



//Create newUser -> Only for propouse of demostration
Route::post('Cr34t3/n3wUs3r', function () {
    $user = User::firstOrCreate(
        ['email' => 'rodrisaiz@icloud.com'],
        [
            'name' => 'CollectiveMindsClient',
            'password' => Hash::make('CollectiveMinds2024'),
        ]
    );

    $user->tokens()->where('name', 'CollectiveMindsClient')->delete();
    $token = $user->createToken('CollectiveMindsClient')->plainTextToken;

    return response()->json(['token' => $token], 201);
});


//Subjects
Route::resource('subject', SubjectController::class)->only(
    'index', 'store', 'show', 'update', 'destroy'
)->middleware('auth:sanctum');

Route::get('subject/email/{email}', [SubjectController::class, 'showByEmail'])->middleware('auth:sanctum');
Route::get('subject/test', [SubjectController::class, 'test']);

//Projects
Route::resource('project', ProjectController::class)->only([
    'index', 'store', 'show', 'update'
])->middleware('auth:sanctum');

Route::get('project/name/{name}', [ProjectController::class, 'showByName'])->middleware('auth:sanctum');

//Assigment of subjects to projects
Route::post('enroll/{subjectId}/{projectId}', [SubjectsInProjectsController::class, 'enroll'])->middleware('auth:sanctum');

//Webhooks
Route::prefix('webhooks')->group(function () {
    Route::resource('subject', WebhookController::class)->only(
        'index', 'store', 'update', 'destroy'
    )->middleware('auth:sanctum');


    Route::resource('project', WebhookController::class)->only(
        'index', 'store', 'update', 'destroy'
    )->middleware('auth:sanctum');
});