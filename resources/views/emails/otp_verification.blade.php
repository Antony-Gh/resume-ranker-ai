@extends('emails.layouts.base')

@section('title', 'OTP Verification')

@section('header')
    @include('emails.components.header', ['title' => 'OTP Verification'])
@endsection

@section('content')
    <p>Hello,</p>
    
    <p>Please use the following One-Time Password (OTP)<br>to verify your {{ config('app.name') }} account:</p>
    
    <div style="text-align: center; margin: 24px 0;">
        <div style="display: inline-block; 
                    padding: 16px 24px; 
                    background: #f8f9fa; 
                    border-radius: 8px; 
                    border: 1px solid #e2e8f0;">
            <span style="font-size: 32px; 
                        font-weight: bold; 
                        letter-spacing: 8px; 
                        color: #1a73e8;
                        font-family: monospace;">
                {{ $otp }}
            </span>
        </div>
    </div>

    <p style="text-align: center; color: #666;">
        This code will expire in 5 minutes.
    </p>

    <p style="font-size: 14px; color: #718096; margin-top: 24px; text-align: center;">
        <strong>Security Tip:</strong> Never share this code with anyone.<br>
        Our team will never ask you for your verification code.
    </p>

    <p style="text-align: center; margin-top: 24px;">
        If you didn't request this code, please ignore this email<br>
        or <a href="{{ config('app.url') }}/contact">contact support</a> if you have concerns.
    </p>

    <div style="text-align: center; margin-top: 32px; font-size: 13px; color: #a0aec0;">
        Your verification code is: <strong>{{ $otp }}</strong>
    </div>
@endsection

@section('footer')
    @include('emails.components.footer')
@endsection