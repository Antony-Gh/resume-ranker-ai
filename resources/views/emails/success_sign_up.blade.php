@extends('emails.layouts.base')

@section('title', "Welcome to {{ config('app.name') }}")

@section('header')
    @include('emails.components.header', ['title' => 'Account Created Successfully'])
@endsection

@section('content')
    <p>Hello {{ $name }},</p>
    <p>Welcome to {{ config('app.name') }}! Your account has been created successfully.</p>
    <p>Click the button below to log in and get started.</p>

    @include('emails.components.button', [
        'url' => config('app.url') . '/login',
        'text' => 'Log In',
    ])

    <p>If you did not sign up for this account
        <br>please ignore this email or <a href="{{ config('app.url') }}/contact">contact support</a>.</p>
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection