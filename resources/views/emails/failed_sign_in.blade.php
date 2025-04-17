@extends('emails.layouts.base')

@section('title', 'Suspicious Login Attempt')

@section('header')
    @include('emails.components.header', ['title' => 'Suspicious Login Attempt'])
@endsection

@section('content')
    <p>Hello {{ $name }},</p>
    <p>We detected an unusual login attempt to your account. The details are as follows:</p>
    <ul>
        <li><strong>Device:</strong> {{ $device }}</li>
        <li><strong>Location:</strong> {{ $location }}</li>
        <li><strong>IP Address:</strong> {{ $ip }}</li>
        <li><strong>Time:</strong> {{ $time }}</li>
    </ul>
    <p>For security reasons, this login was blocked. If this was you, you may need to verify your identity.</p>
    <p>If you did not attempt to log in, please change your password immediately.</p>

    @include('emails.components.button', [
        'url' => config('app.url') . '/reset-password',
        'text' => 'Secure Your Account',
    ])
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection