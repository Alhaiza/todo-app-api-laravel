<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (user must be authenticated via token)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo', TodoController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
