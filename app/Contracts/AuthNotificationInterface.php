<?php

namespace App\Contracts;

interface AuthNotificationInterface
{
    public function sendSignUpSuccess(string $email, string $name): void;
    
    public function sendSignInSuccess(
        string $email,
        string $name,
        string $device,
        string $location,
        string $ip,
        string $time
    ): void;
    
    public function sendPasswordResetSuccess(string $email, string $name): void;
    
    public function sendOtpVerificationSuccess(string $email, string $name): void;
    
    public function sendPasswordReset(string $email, string $token): void;
    
    public function sendOtpVerification(
        string $email,
        string $otp,
        int $expiresInMinutes = 5
    ): void;
    
    public function sendFailedSignIn(
        string $email,
        string $name,
        string $device,
        string $location,
        string $ip,
        string $time
    ): void;
}