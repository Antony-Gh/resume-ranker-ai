<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPVerificationController;

/**
 * Authentication Routes
 * 
 * Organized for clarity and security:
 * - Guest-Only Routes (For non-authenticated users)
 * - Social Authentication Routes (Google, GitHub, LinkedIn)
 * - Protected Routes (Require authentication)
 */

/**
 * 🚀 Guest-Only Routes
 * - Accessible only to non-authenticated users
 * - Rate limiting (5 requests per minute)
 */
Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Login Routes
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');

        // Register Routes
        Route::get('/register', 'showRegisterForm')->name('register');
        Route::post('/register', 'register');

        // Password Reset Routes
        Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
        Route::post('/forgot-password', 'forgotPassword')->name('password.email');

        Route::get('/reset-password/{token}', 'showResetPasswordForm')->name('password.reset');

        Route::get('/reset-password-no-token', 'showResetPasswordFormWithoutToken')->name('password.reset.no.token');
        
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
});

/**
 * 🚀 Social Authentication Routes
 * - Accessible only to non-authenticated users
 * - Rate limiting (5 requests per minute)
 */
Route::middleware(['guest', 'throttle:5,1'])->group(function () {
    Route::controller(SocialLoginController::class)->group(function () {
        Route::get('auth/{provider}', 'redirectToProvider')
            ->name('social.login')
            ->where('provider', 'google|github|linkedin');

        Route::get('auth/{provider}/callback', 'handleProviderCallback')
            ->where('provider', 'google|github|linkedin');

        Route::get('/auth/redirect/{provider}', 'redirectToProvider')
            ->name('social.redirect');
    });
});

/**
 * 🚀 Protected Authentication Routes
 * - Require authentication
 * - Rate limiting (5 requests per minute)
 */
Route::middleware(['auth', 'throttle:5,1'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Logout (NO email verification required)
        Route::post('/logout', 'logout')->name('logout');



        // OTP Verification Routes
        Route::post('/send-otp', [OTPVerificationController::class, 'sendOTP'])->name('send.otp');
        Route::post('/verify-otp', [OTPVerificationController::class, 'verifyOTP'])->name('verify.otp');


        // Password Confirmation (Requires email verification)
        Route::middleware(['verified'])->group(function () {
            Route::post('/profile', 'profile')->name('profile');
            Route::get('/confirm-password', 'showPasswordConfirmationForm')->name('password.confirm');
            Route::post('/confirm-password', 'confirmPassword')->name('password.confirm.store');
        });

        // Email Verification Routes
        Route::get('/verify-email', 'showVerificationNotice')->name('verification.notice');
        Route::get('/verify-email/{id}/{hash}', 'verifyEmail')
            ->middleware(['signed'])
            ->name('verification.verify');
        Route::post('/email/verification-notification', 'resendVerification')->name('verification.send');
    });
});
