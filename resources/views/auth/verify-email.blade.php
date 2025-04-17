@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email Address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Thanks for signing up! Before getting started, please verify your email address.
            </p>
        </div>

        <div class="mt-8 space-y-6">
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Didn't receive the verification email?
                </p>
                <button id="" class="mt-2 font-medium text-indigo-600 hover:text-indigo-500">
                    Click here to resend
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already verified? 
                    <a href="{{ route('dashboard') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Go to dashboard
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
