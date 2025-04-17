@extends('emails.layouts.base')

@section('title', 'Email Verified Successfully')

@section('header')
    @include('emails.components.header', ['title' => 'Email Verified'])
@endsection

@section('content')
    <p>Hello {{ $name }},</p>
    <p>Your email address has been successfully verified. You can now enjoy full access to your account.</p>
    <p>Click the button below to log in.</p>

    @include('emails.components.button', [
        'url' => config('app.url') . '/login',
        'text' => 'Log In',
    ])
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection