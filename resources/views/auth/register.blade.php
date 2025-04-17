@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Sign Up</h2>
        <form method="POST" action="{{ route('register') }}">
            {{-- @csrf --}}
            <div class="input-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>

        <x-social-login-buttons :providers="['google', 'github', 'linkedin']" />

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
@endsection