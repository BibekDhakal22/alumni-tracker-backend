<?php
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MentorshipController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/alumni',    [AlumniController::class, 'index']);
Route::get('/jobs',      [JobController::class, 'index']);
Route::get('/events', [EventController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',          [AuthController::class, 'logout']);
    Route::get('/me',               [AuthController::class, 'me']);
    Route::get('/alumni',           [AlumniController::class, 'index']);
    Route::put('/profile/update',   [AlumniController::class, 'updateProfile']);
    Route::delete('/alumni/{id}',   [AlumniController::class, 'destroy']);
    Route::post('/jobs',            [JobController::class, 'store']);
    Route::delete('/jobs/{id}',     [JobController::class, 'destroy']);
    Route::get('/analytics',        [AnalyticsController::class, 'index']);
    Route::get('/alumni/export', [AlumniController::class, 'export']);
    Route::post('/events',         [EventController::class, 'store']);
    Route::delete('/events/{id}',  [EventController::class, 'destroy']);
    Route::get('/mentors',                      [MentorshipController::class, 'mentors']);
Route::post('/mentorship',                  [MentorshipController::class, 'store']);
Route::get('/mentorship/my-requests',       [MentorshipController::class, 'myRequests']);
Route::get('/mentorship/received',          [MentorshipController::class, 'receivedRequests']);
Route::put('/mentorship/{id}/respond',      [MentorshipController::class, 'respond']);
});