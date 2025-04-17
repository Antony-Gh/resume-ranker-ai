@extends('emails.layouts.base')

@section('title', 'Reset Your Password')

@section('header')
    @include('emails.components.header', ['title' => 'Password Reset Request'])
@endsection

@section('content')
    @php
        $url = config('app.url') . '/reset-password/' . $token . '?email=' . urlencode($email); // Define the URL variable
    @endphp

    <p>Hello,</p>
    <p>We received a request to reset your password<br>for your {{ config('app.name') }} account.</p>
    <p>Click the button below to reset your password.<br>This link will expire in 30 minutes.</p>


    @include('emails.components.button', [
        'url' => $url,
        'text' => 'Reset Password',
    ])



    <p style="margin-top: 24px;">
        If you're having trouble clicking the button <br> copy and paste this URL into your browser:
    <div style="word-break: break-all;">{{ $url }}</div>
    </p>

    <p style="font-size: 12px; color: #999; margin-top: 8px;">
        Request originated from: {{ request()->ip() }}
    </p>

    <p style="margin-top: 24px;">
        If you didn't request this password reset <br> please ignore this email or
        <a href="{{ config('app.url') }}/contact">contact support</a> if you have concerns.
    </p>

    <p style="font-size: 14px; color: #718096; margin-top: 24px;">
        <strong>Security Tip:</strong> Never share your password or this link with anyone.
        <br>Our team will never ask you for your password.
    </p>
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection
