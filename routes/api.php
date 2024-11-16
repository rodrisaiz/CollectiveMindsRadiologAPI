<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/rodri', function (Request $request) {
    return "Hello rodri";
});

Route::get('/rodri2', function (Request $request) {
    return "Hello rodri autenticated!!!!";
})->middleware('auth:sanctum');


Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});