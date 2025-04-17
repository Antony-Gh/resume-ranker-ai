<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
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
