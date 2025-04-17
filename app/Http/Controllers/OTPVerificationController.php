<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use App\Contracts\AuthNotificationInterface;

class OTPVerificationController extends Controller
{
    use ResponseTrait;

    protected AuthNotificationInterface $authNotifier;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthNotificationInterface $authNotifier)
    {
        $this->authNotifier = $authNotifier;
    }

    public function sendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in cache for 5 minutes
            Cache::put("otp_{$email}", $otp, now()->addMinutes(5));

            // Send OTP email
            // Send OTP via email
            $this->authNotifier->sendOtpVerification(
                $email,
                $otp,
                5
            );


            return $this->sendResponse('OTP sent successfully', [
                'expires_in' => 300 // 5 minutes in seconds
            ]);
        } catch (\Exception $e) {
            return $this->sendError('Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6'
            ]);

            $email = $request->email;
            $otp = $request->otp;

            $cachedOTP = Cache::get("otp_{$email}");

            if (!$cachedOTP) {
                return $this->sendBadRequest('OTP has expired');
            }

            if ($cachedOTP !== $otp) {
                return $this->sendBadRequest('Invalid OTP');
            }

            // Clear the OTP from cache
            Cache::forget("otp_{$email}");

            $user = User::where('email', filter_var($request->email, FILTER_SANITIZE_EMAIL))->first();

            if ($user) {
                if ($user->hasVerifiedEmail()) {
                    return $this->sendResponse('Email already verified');
                }

                // Update email_verified_at with the current timestamp
                $user->update(['email_verified_at' => now()]);

                // OTP is valid - send success notification
                $this->authNotifier->sendOtpVerificationSuccess(
                    $user->email,
                    $user->name
                );

                return $this->sendResponse('OTP verified successfully');
            } else {
                return $this->sendNotFound('User not found');
            }

            // Here you can add your logic for what happens after successful verification
            // For example, marking the email as verified in the database


        } catch (\Exception $e) {
            return $this->sendError('Failed to verify OTP: ' . $e->getMessage());
        }
    }
}
