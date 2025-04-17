<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthenticationTrait;
use App\Http\Traits\ResponseTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Contracts\AuthNotificationInterface;
use Illuminate\Support\Facades\Cache;

/**
 * Authentication Controller
 * 
 * Handles all authentication-related functionality including:
 * - Login/Logout
 * - Registration
 * - Password Reset
 * - Email Verification
 * - Social Authentication
 * - Session Management
 */
class AuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, AuthenticationTrait, ResponseTrait;

    protected AuthNotificationInterface $authNotifier;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthNotificationInterface $authNotifier)
    {
        // Apply guest middleware to all methods except logout
        $this->middleware('guest', ['except' => ['logout', 'verifyEmail', 'resendVerification']]);

        // Apply API middleware to authentication endpoints
        $this->middleware('api', ['only' => ['login', 'register', 'logout']]);

        // Apply rate limiting to sensitive endpoints
        $this->middleware('throttle:5,1', ['only' => ['login', 'forgotPassword']]);
        $this->middleware('throttle:6,1', ['only' => ['resendVerification']]);

        $this->authNotifier = $authNotifier;
    }


    public function showLoginForm()
    {
        return redirect()->route('realHome')->with([
            'show_password_reset' => false,
            'reset_token' => '',     
            'reset_email' => '',    
            'action' => 'signin',           
        ]);
    }

    public function showRegisterForm()
    {
        return redirect()->route('realHome')->with([
            'show_password_reset' => false,
            'reset_token' => '',     
            'reset_email' => '',    
            'action' => 'signup',           
        ]);
    }

    public function showForgotPasswordForm()
    {
        return redirect()->route('realHome')->with([
            'show_password_reset' => false,
            'reset_token' => '',     
            'reset_email' => '',    
            'action' => 'forgot',           
        ]);
    }

    public function showVerificationNotice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors()->toArray());
            }
            $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
            if ($rateLimitResponse = $this->handleRateLimiting($request)) {
                return $rateLimitResponse;
            }
            if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))){
                $user2 = User::where('email', $request->email)->first();
                if ($user2) {
                    $this->authNotifier->sendFailedSignIn(
                        $user2->email,
                        $user2->name,
                        $request->header('User-Agent', 'Unknown device'),
                        $this->getLocation($request->ip()),
                        $request->ip(),
                        now()->toDateTimeString()
                    );
                }
                $this->logAuthAttempt('login', ['email' => $request->email, 'status' => 'failed']);
                return $this->sendUnauthorized('Invalid email or password.');
            }
            RateLimiter::clear($this->throttleKey($request));
            $user = User::where('email', $request->email)->first();
            $this->logAuthAttempt('login', ['email' => $email, 'status' => 'success']);
            $this->authNotifier->sendSignInSuccess(
                $user->email,
                $user->name,
                $request->header('User-Agent', 'Unknown device'),
                $this->getLocation($request->ip()), // Implement geolocation
                $request->ip(),
                now()->toDateTimeString()
            );
            $csrfToken = $this->handleSessionRegeneration($request);
            $token = $user->createToken('auth_token')->plainTextToken;
            $this->detectSuspiciousActivity($user, $request);
            $user->updateLastLogin();
            return $this->sendResponse('Login successful', [
                'token' => $token,
                'csrf_token' => $csrfToken,
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            $this->logAuthError('login', $th, ['email' => $request->email ?? null]);
            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        Log::info($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s-]+$/u'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => [
                    'required',
                    'confirmed',
                    PasswordRules::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                ],
                'password_confirmation' => ['required', 'same:password']
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors()->toArray());
            }

            $user = User::create([
                'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60)
            ]);

            Auth::login($user);

            // Send sign-up success notification
            $this->authNotifier->sendSignUpSuccess($user->email, $user->name);

            $this->logAuthAttempt('registration', [
                'email' => $user->email,
                'status' => 'success'
            ]);

            // return $this->sendCreated('Registration successful', [
            //     'redirect' => route('dashboard')
            // ]);

            $newUser = User::where('email', $request->email)->first();

            // ✅ Regenerate session for security
            $csrfToken = $this->handleSessionRegeneration($request);

            $token = $newUser->createToken('auth_token')->plainTextToken;

            $newUser->updateLastLogin();

            return $this->sendResponse('Registration successful', [
                'token' => $token,
                'csrf_token' => $csrfToken,
                'user' => $newUser,
            ]);
        } catch (\Throwable $th) {
            $this->logAuthError('registration', $th, ['email' => $request->email ?? null]);
            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $userAuth = Auth::user();

            if (!$userAuth) {
                return $this->sendUnauthorized('User not authenticated.');
            }
            $user = User::where('email', $userAuth->email)->first();

            // ✅ Revoke all API tokens (if using Laravel Sanctum)
            $user->tokens()->delete();

            Auth::logout();

            $this->handleSessionInvalidation($request);

            $this->logAuthAttempt('logout', ['email' => $user->email, 'status' => 'success']);

            return $this->sendResponse('Logged out successfully', [
                'redirect' => route('realHome')
            ]);
        } catch (\Throwable $th) {
            $this->logAuthError('logout', $th);
            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Handle forgot password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            // ✅ Validate request
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors()->toArray());
            }

            $email = $request->email;

            // ✅ Check rate limiting (prevent spamming)
            $existingRequest = DB::table('custom_password_resets')
                ->where('email', $email)
                ->first();

            if ($existingRequest) {
                if ($existingRequest->attempts >= 3 && Carbon::parse($existingRequest->expires_at)->isFuture()) {
                    return $this->sendTooManyRequests('Too many attempts. Try again later.');
                }

                // ✅ If the token is expired, reset attempts count
                if (Carbon::parse($existingRequest->expires_at)->isPast()) {
                    DB::table('custom_password_resets')->where('email', $email)->delete();
                }
            }


            // // ✅ Check if a valid (non-expired) token exists
            // if ($existingRequest && Carbon::parse($existingRequest->expires_at)->isFuture()) {
            //     return $this->sendBadRequest('A reset link has already been sent. Please check your email.');
            // }

            // ✅ Generate a secure reset token
            $token = Str::random(64);

            // ✅ Store the reset token (delete expired token if it exists)
            DB::table('custom_password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $token,
                    'created_at' => now(),
                    'expires_at' => now()->addMinutes(30), // Token expires in 30 minutes
                    'attempts' =>  DB::raw(
                        $existingRequest &&
                            Carbon::parse($existingRequest->expires_at)->isFuture()
                            ? 'attempts + 1' : '1'
                    ) // Initialize or increment attempts & Reset if expired
                ]
            );

            // ✅ Send the password reset email
            // Send password reset email using the Mailable class
            try {
                // Send password reset email
                $this->authNotifier->sendPasswordReset($email, $token);
            } catch (\Exception $mailException) {
                return $this->sendServerError('Failed to send email. Please try again later.');
            }

            return $this->sendResponse('A password reset link has been sent to your email.');
        } catch (\Throwable $th) {
            $this->logAuthError('forgot_password', $th, ['email' => $request->email ?? null]);
            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Verify if the provided password reset token is valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyResetToken(Request $request): JsonResponse
    {
        try {
            // ✅ Validate input request
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users,email'], // Ensure email exists in users table
                'token' => ['required', 'string'], // Token must be provided
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors()->toArray());
            }

            // ✅ Retrieve the password reset record for the provided email and token
            $record = DB::table('custom_password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            // ❌ If no record found, token is invalid or does not exist
            if (!$record) {
                return $this->sendBadRequest('Invalid or expired reset token.');
            }

            // ❌ Check if the token has expired
            if (Carbon::parse($record->expires_at)->isPast()) {
                return $this->sendBadRequest('Token has expired.');
            }

            // ✅ Token is valid
            return $this->sendResponse('Token is valid.');
        } catch (\Throwable $th) {
            // 🛑 Log any unexpected errors during verification
            $this->logAuthError('verify_reset_token', $th, [
                'email' => $request->email ?? null,
                'token' => $request->token ?? null
            ]);

            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }

    /**
     * Display the password reset form if the provided token is valid.
     *
     * @param  string  $token  The password reset token.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function showResetPasswordFormWithoutToken(Request $request)
    {
        $email = $request->query('email');
        // ✅ Redirect to 'realHome' with necessary data to show the password reset modal
        return redirect()->route('realHome')->with([
            'show_password_reset' => false,
            'reset_token' => '',
            'reset_email' => $email,
            'action' => 'forgot',
        ]);
    }

    /**
     * Display the password reset form if the provided token is valid.
     *
     * @param  string  $token  The password reset token.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function showResetPasswordForm($token, Request $request)
    {
        try {
            // ✅ Retrieve email from query parameters
            $email = $request->query('email');

            // ❌ Ensure email is provided
            if (!$email) {
                return $this->sendBadRequest('Email parameter is missing.');
            }

            // ✅ Verify if the token exists and is valid for the given email
            $record = DB::table('custom_password_resets')
                ->where('email', $email)
                ->where('token', $token)
                ->first();

            // ❌ If no matching record found, return an error response
            if (!$record) {
                return $this->sendBadRequest('Invalid or expired reset token.');
            }

            // ❌ Check if the token has expired
            if (Carbon::parse($record->expires_at)->isPast()) {
                return $this->sendBadRequest('Token has expired.');
            }

            // ✅ Redirect to 'realHome' with necessary data to show the password reset modal
            return redirect()->route('realHome')->with([
                'show_password_reset' => true, // Trigger modal display
                'reset_token' => $token,       // Pass the reset token
                'reset_email' => $email,       // Pass the user's email
                'action' => 'reset',           // Indicate reset action
            ]);
        } catch (\Throwable $th) {
            // 🛑 Log any unexpected errors
            $this->logAuthError('show_reset_password_form', $th, [
                'email' => $email ?? null,
                'token' => $token ?? null
            ]);

            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }


    /**
     * Handle password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            // ✅ Validate request data
            $validator = Validator::make($request->all(), [
                'token' => ['required', 'string'], // Ensure a token is provided
                'email' => ['required', 'email', 'exists:users,email'], // Validate email existence
                'password' => [
                    'required',
                    'confirmed', // Ensure password confirmation matches
                    PasswordRules::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised() // Ensure password is not compromised
                ],
            ]);

            // ❌ Return validation errors if any
            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors()->toArray());
            }

            // ✅ Retrieve password reset record
            $record = DB::table('custom_password_resets')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            // ❌ Check if the token is invalid or expired
            if (!$record || Carbon::parse($record->expires_at)->isPast()) {
                return $this->sendBadRequest('Invalid or expired reset token.');
            }

            // ✅ Fetch the user
            $user = User::where('email', $request->email)->first();

            // ❌ If user is not found, return an error
            if (!$user) {
                return $this->sendNotFound('User not found.');
            }

            // ✅ Update the user's password securely
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // After successful password reset
            $this->authNotifier->sendPasswordResetSuccess(
                $user->email,
                $user->name
            );

            // ✅ Remove used reset token from the database to prevent reuse
            DB::table('custom_password_resets')->where('email', $request->email)->delete();

            return $this->sendResponse('Password reset successfully.');
        } catch (\Throwable $th) {
            // 🛑 Log unexpected errors for debugging
            $this->logAuthError('reset_password', $th, ['email' => $request->email ?? null]);

            return $this->sendServerError('An unexpected error occurred. Please try again later.');
        }
    }


    /**
     * Generate a unique throttle key for rate limiting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }

    private function getLocation(string $ip): string
    {
        try {
            // Using ipinfo.io to get geolocation data
            $accessToken = env('IPINFO_API_KEY');  // Add your API key in .env
            $url = "http://ipinfo.io/{$ip}?token={$accessToken}";
            $response = file_get_contents($url);

            if ($response) {
                $data = json_decode($response, true);
                // Return a formatted location (city, region, country)
                return "{$data['city']}, {$data['region']}, {$data['country']}";
            }
        } catch (\Exception $e) {
            // Log error if something goes wrong with the request
            Log::error('Error fetching geolocation', ['ip' => $ip, 'error' => $e->getMessage()]);
        }

        return 'Unknown location'; // Default if service fails
    }


    private function detectSuspiciousActivity(User $user, Request $request): bool
    {
        $currentIp = $request->ip();
        $currentUserAgent = $request->header('User-Agent', 'Unknown device');

        // Retrieve last known login data
        $lastKnownIp = $user->last_login_ip;
        $lastKnownUserAgent = $user->last_login_user_agent; // Store this in DB
        $lastKnownLocation = $this->getLocation($lastKnownIp);
        $currentLocation = $this->getLocation($currentIp);

        // Define suspicious activity conditions
        $ipChanged = $lastKnownIp && $lastKnownIp !== $currentIp;
        $locationChanged = $lastKnownLocation && $lastKnownLocation !== $currentLocation;
        $deviceChanged = $lastKnownUserAgent && $lastKnownUserAgent !== $currentUserAgent;

        if ($ipChanged || $locationChanged || $deviceChanged) {
            // Log suspicious attempt for further review
            Log::warning("Suspicious login attempt detected for user {$user->email}", [
                'old_ip' => $lastKnownIp,
                'new_ip' => $currentIp,
                'old_location' => $lastKnownLocation,
                'new_location' => $currentLocation,
                'old_device' => $lastKnownUserAgent,
                'new_device' => $currentUserAgent,
                'time' => now()->toDateTimeString(),
            ]);

            // Prevent repeated alerts for the same change within a time window
            if (!$this->recentlyNotified($user, $currentIp, $currentUserAgent)) {
                $this->authNotifier->sendFailedSignIn(
                    $user->email,
                    $user->name,
                    $currentUserAgent,
                    $currentLocation,
                    $currentIp,
                    now()->toDateTimeString()
                );

                // Store notification timestamp to prevent alert spam
                Cache::put("suspicious_login:{$user->id}", now(), now()->addMinutes(30));
            }

            return true;
        }

        return false;
    }

    /**
     * Checks if the user has been notified recently to avoid duplicate alerts.
     */
    private function recentlyNotified(User $user, string $ip, string $userAgent): bool
    {
        return Cache::has("suspicious_login:{$user->id}");
    }
}
