<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\JobController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/alumni',    [AlumniController::class, 'index']);
Route::get('/jobs',      [JobController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',          [AuthController::class, 'logout']);
    Route::get('/me',               [AuthController::class, 'me']);
    Route::put('/profile/update',   [AlumniController::class, 'updateProfile']);
    Route::delete('/alumni/{id}',   [AlumniController::class, 'destroy']);
    Route::post('/jobs',            [JobController::class, 'store']);
    Route::delete('/jobs/{id}',     [JobController::class, 'destroy']);
});