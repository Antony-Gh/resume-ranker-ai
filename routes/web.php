<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::get('/home', [DashboardController::class, 'home'])->name('realHome');

Route::get('/pricing', [DashboardController::class, 'pricing'])->name('pricing');
Route::get('/resume-rankings', [DashboardController::class, 'resumeRankings'])->name('resumeRankings');
Route::get('/subscription-management', [DashboardController::class, 'subscriptionManagement'])->name('subscriptionManagement');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions');
});


// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/google/callback', function () {
//     $user = Socialite::driver('google')->user();
//     // Handle authentication
// });

Route::get('auth/{provider}', [SocialLoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);

Route::post('/subscribe', function (Request $request) {
    $user = Auth::user();
    $user->newSubscription('default', 'price_12345')->create($request->paymentMethod);
});



require __DIR__.'/auth.php';
