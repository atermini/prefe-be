<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Api\TodayQuestionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->middleware('throttle:20,1')
    ->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/questions/today', [TodayQuestionController::class, 'show']);
    Route::post('/questions/{question}/answer', [AnswerController::class, 'store']);

    Route::get('/friendships', [FriendshipController::class, 'index']);
    Route::post('/friendships', [FriendshipController::class, 'store']);
    Route::patch('/friendships/{friendship}', [FriendshipController::class, 'update']);

    Route::get('/feed', [FeedController::class, 'index']);
    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
});
