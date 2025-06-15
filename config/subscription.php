<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | This configuration file contains the subscription plans available
    | in the application. Each plan has a unique ID, name, description,
    | price, and features.
    |
    */

    'plans' => [
        'basic' => [
            'id' => 'basic',
            'name' => 'Basic Plan',
            'description' => 'Basic features for individuals',
            'price' => [
                'monthly' => 9.99,
                'yearly' => 99.99,
            ],
            'features' => [
                'Resume parsing',
                'Basic analytics',
                'Up to 50 resumes per month',
                'Email support',
            ],
            'limits' => [
                'resumes_per_month' => 50,
                'users' => 1,
                'exports' => 10,
            ],
        ],
        'pro' => [
            'id' => 'pro',
            'name' => 'Professional Plan',
            'description' => 'Advanced features for professionals',
            'price' => [
                'monthly' => 19.99,
                'yearly' => 199.99,
            ],
            'features' => [
                'All Basic features',
                'Advanced analytics',
                'Up to 200 resumes per month',
                'Priority email support',
                'Custom ranking criteria',
                'Export to PDF/Excel',
            ],
            'limits' => [
                'resumes_per_month' => 200,
                'users' => 1,
                'exports' => 50,
            ],
        ],
        'business' => [
            'id' => 'business',
            'name' => 'Business Plan',
            'description' => 'Complete solution for businesses',
            'price' => [
                'monthly' => 49.99,
                'yearly' => 499.99,
            ],
            'features' => [
                'All Professional features',
                'Unlimited resumes',
                'Team collaboration',
                'API access',
                'Priority phone support',
                'Custom integrations',
                'Dedicated account manager',
            ],
            'limits' => [
                'resumes_per_month' => -1, // Unlimited
                'users' => 5,
                'exports' => -1, // Unlimited
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | The available payment methods for subscriptions.
    |
    */
    'payment_methods' => [
        'credit_card' => [
            'name' => 'Credit Card',
            'description' => 'Pay with Visa, Mastercard, or American Express',
            'enabled' => true,
        ],
        'paypal' => [
            'name' => 'PayPal',
            'description' => 'Pay with your PayPal account',
            'enabled' => true,
        ],
        'bank_transfer' => [
            'name' => 'Bank Transfer',
            'description' => 'Pay via bank transfer',
            'enabled' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscription Settings
    |--------------------------------------------------------------------------
    |
    | General settings for subscriptions.
    |
    */
    'trial_days' => 14,
    'max_subscription_months' => 36, // 3 years
    'default_currency' => 'USD',
    'currencies' => [
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
        ],
        'EUR' => [
            'name' => 'Euro',
            'symbol' => '€',
        ],
        'GBP' => [
            'name' => 'British Pound',
            'symbol' => '£',
        ],
    ],
];
