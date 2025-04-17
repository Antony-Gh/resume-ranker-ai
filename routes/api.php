<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;

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
