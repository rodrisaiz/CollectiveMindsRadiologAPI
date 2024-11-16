<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/rodri', function (Request $request) {
    return "Hello rodri in V2";
});

Route::get('/rodri2', function (Request $request) {
    return "Hello rodri autenticated in V2!!!!";
})->middleware('auth:sanctum');
