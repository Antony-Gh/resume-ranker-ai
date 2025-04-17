@extends('emails.layouts.base')

@section('title', 'Password Reset Successful')

@section('header')
    @include('emails.components.header', ['title' => 'Password Reset Successful'])
@endsection

@section('content')
    <p>Hello {{ $name }},</p>
    <p>Your password for your {{ config('app.name') }} account has been successfully reset.</p>
    <p>If you did not initiate this change, please reset your password immediately and contact our support team.</p>

    @include('emails.components.button', [
        'url' => config('app.url') . '/login',
        'text' => 'Login to Your Account',
    ])

    <p style="margin-top: 24px;">
        If you're having trouble clicking the button <br> copy and paste this URL into your browser:
    <div style="word-break: break-all;">{{ config('app.url') }}/login</div>
    </p>

    <p style="font-size: 12px; color: #999; margin-top: 8px;">
        Request originated from: {{ request()->ip() }}
    </p>

    <p style="margin-top: 24px;">
        If this wasn't you, we strongly recommend changing your password again and
        <a href="{{ config('app.url') }}/contact">contacting support</a> for assistance.
    </p>

    <p style="font-size: 14px; color: #718096; margin-top: 24px;">
        <strong>Security Tip:</strong> Ensure your password is strong and unique.
        <br>Never share your credentials with anyone.
        <br>Our team will never ask for your password.
    </p>
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection
