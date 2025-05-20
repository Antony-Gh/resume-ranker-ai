<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;

use App\Models\SaverCategory;
use App\Policies\SaverCategoryPolicy;
use App\Models\User;
use App\Policies\UserPolicy;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        SaverCategory::class => SaverCategoryPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Disable the default email verification notification
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return null; // Prevents sending email
        });

        // Disable the default password reset email
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return null; // Prevents sending email
        });
    }
}
