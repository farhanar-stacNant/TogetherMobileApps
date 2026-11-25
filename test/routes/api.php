<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Together App
|--------------------------------------------------------------------------
*/

// Public Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (requires Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Current User & Profile (Step 4)
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Home Feed (Step 5) & Users / People near you
    Route::get('/users/nearby', [UserController::class, 'nearby']);
    Route::get('/users', [UserController::class, 'index']);

    // Events & Moments (Step 5, 6, 8)
    Route::get('/events/upcoming', [EventController::class, 'upcoming']);
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::post('/events/{id}/join', [EventController::class, 'toggleJoin']);

    // Chats & Messages (Step 7)
    Route::get('/conversations', [ChatController::class, 'index']);
    Route::post('/conversations/direct', [ChatController::class, 'startDirectChat']);
    Route::get('/conversations/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/conversations/{id}/messages', [ChatController::class, 'sendMessage']);
});
