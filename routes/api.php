<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\SaverUserController;
use App\Http\Controllers\SaverPasswordController;
use App\Http\Controllers\SaverCategoryController;

/**
 * API Routes
 *
 * This file contains all API routes for the application.
 * Routes are organized by authentication status and functionality.
 */

/**
 * Public API Routes
 * - No authentication required
 * - Rate limiting applied for security
 */
Route::middleware('throttle:60,1')->group(function () {
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset Routes
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);



    // Authenticated routes
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        // User routes
        Route::get('/user', [SaverUserController::class, 'showCurrent']);
        Route::put('/user', [SaverUserController::class, 'updateCurrent']);
        Route::delete('/user', [SaverUserController::class, 'destroyCurrent']);

        // Admin-only user management
        Route::middleware('can:manage-users')->group(function () {
            Route::get('/users', [SaverUserController::class, 'index'])
                ->middleware('throttle:60,1');
            Route::get('/users/{user}', [SaverUserController::class, 'show']);
            Route::put('/users/{user}', [SaverUserController::class, 'update']);
            Route::delete('/users/{user}', [SaverUserController::class, 'destroy']);
            Route::delete('/users/force/{user}', [SaverUserController::class, 'forceDelete']);
            Route::patch('/users/restore/{user}', [SaverUserController::class, 'restore']);
        });

        // Password routes
        Route::apiResource('passwords', SaverPasswordController::class)
            ->except(['index']);
        Route::get('/passwords', [SaverPasswordController::class, 'index'])
            ->middleware('throttle:60,1');
        Route::delete('/passwords/force/{password}', [SaverPasswordController::class, 'forceDelete']);
        Route::patch('/passwords/restore/{password}', [SaverPasswordController::class, 'restore']);

        // Category routes
        Route::apiResource('categories', SaverCategoryController::class);
    });
});

/**
 * Protected API Routes
 * - Require authentication via Sanctum
 * - Rate limiting applied
 * - Email verification required for sensitive operations
 */
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // Authentication Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail']);

    // User Information
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
