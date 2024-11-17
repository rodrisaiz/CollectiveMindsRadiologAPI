<?php

use App\Http\V1\Controllers\ProjectController;
use App\Http\V1\Controllers\SubjectController;
use App\Http\V1\Controllers\SubjectsInProjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
