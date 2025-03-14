@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Sign Up</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
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
        <div class="auth-divider">OR</div>
        <div class="social-login">
            <a href="{{ route('social.login', 'google') }}" class="btn btn-google">Google</a>
            <a href="{{ route('social.login', 'facebook') }}" class="btn btn-facebook">Facebook</a>
        </div>
    </div>
</div>
@endsection