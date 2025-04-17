@extends('emails.layouts.base')

@section('title', 'New Device Login')

@section('header')
    @include('emails.components.header', ['title' => 'New Device Login Detected'])
@endsection

@section('content')
    @php
        $url = config('app.url') . '/reset-password-no-token' . '?email=' . urlencode($email); // Define the URL variable
    @endphp
    <p>Hello {{ $name }},</p>
    <p>We noticed a login to your account from a new device:</p>
    <ul>
        <li><strong>Device:</strong> {{ $device }}</li>
        <li><strong>Location:</strong> {{ $location }}</li>
        <li><strong>IP Address:</strong> {{ $ip }}</li>
        <li><strong>Time:</strong> {{ $time }}</li>
    </ul>
    <p>If this was you, no action is needed. If you don’t recognize this login, please reset your password immediately.</p>

    @include('emails.components.button', [
        'url' => $url,
        'text' => 'Reset Password',
    ])
@endsection

@section('footer')
    @include('emails.components.footer')
    @endsectio
