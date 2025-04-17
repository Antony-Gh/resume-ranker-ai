<?php

namespace App\Services;

use App\Contracts\AuthNotificationInterface;
use App\Mail\{
    SuccessSignUpMail,
    SuccessSignInMail,
    SuccessResetPasswordMail,
    SuccessOtpVerificationMail,
    ResetPasswordMail,
    OtpVerificationMail,
    FailedSignInMail
};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthNotificationService implements AuthNotificationInterface
{
    private function sendEmail(string $email, $mailable, string $logMessage): void
    {
        try {
            Mail::to($email)->queue($mailable);
            Log::info("{$logMessage} queued for {$email}");
        } catch (Exception $e) {
            Log::error("Failed to queue {$logMessage} for {$email}: " . $e->getMessage());
            throw $e;
        }
    }

    public function sendSignUpSuccess(string $email, string $name): void
    {
        $this->sendEmail($email, new SuccessSignUpMail($email, $name), 'Sign-up success email');
    }

    public function sendSignInSuccess(string $email, string $name, string $device, string $location, string $ip, string $time): void
    {
        $this->sendEmail($email, new SuccessSignInMail($email, $name, $device, $location, $ip, $time), 'Sign-in success email');
    }

    public function sendPasswordResetSuccess(string $email, string $name): void
    {
        $this->sendEmail($email, new SuccessResetPasswordMail($email, $name), 'Password reset success email');
    }

    public function sendOtpVerificationSuccess(string $email, string $name): void
    {
        $this->sendEmail($email, new SuccessOtpVerificationMail($email, $name), 'OTP verification success email');
    }

    public function sendPasswordReset(string $email, string $token): void
    {
        $this->sendEmail($email, new ResetPasswordMail($token, $email), 'Password reset email');
    }

    public function sendOtpVerification(string $email, string $otp, int $expiresInMinutes = 5): void
    {
        $this->sendEmail($email, new OtpVerificationMail($otp, $email, $expiresInMinutes), 'OTP verification email');
    }

    public function sendFailedSignIn(string $email, string $name, string $device, string $location, string $ip, string $time): void
    {
        $this->sendEmail($email, new FailedSignInMail($email, $name, $device, $location, $ip, $time), 'Failed sign-in attempt email');
    }
}
