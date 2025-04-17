<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 * 
 * This file contains all web routes for the application.
 * Routes are organized by authentication status and functionality.
 */

/**
 * Public Routes
 * - No authentication required
 * - Accessible to all visitors
 */
Route::middleware(['web'])->group(function () {
    // Landing Pages
    Route::get('/', [DashboardController::class, 'myHome'])->name('myHome');
    Route::get('/home', [DashboardController::class, 'realHome'])->name('realHome');
    Route::get('/pricing', [DashboardController::class, 'pricing'])->name('pricing');
    Route::get('/resume-rankings', [DashboardController::class, 'resumeRankings'])->name('resumeRankings');
});

/**
 * Protected Routes
 * - Require authentication
 * - Require email verification
 * - Rate limiting applied for sensitive operations
 */
Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {
    // Dashboard & Main Application
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/main', [DashboardController::class, 'mainHome'])->name('signedHome');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Subscription Management
    Route::get('/subscription-management', [DashboardController::class, 'subscriptionManagement'])
        ->name('subscriptionManagement');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
});

// routes/web.php or routes/api.php
Route::middleware('web')->get('/sanctum/csrf-cookie', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

// Include authentication routes
require __DIR__ . '/auth.php';
