@extends('layouts.guest')

@section('title', 'Resume Ranker AI - Home')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/HomeOne1.css') }}">

    <link rel="stylesheet" href="{{ mix('css/auth.css') }}">
    <link rel="stylesheet" href="{{ mix('css/SignUpOne.css') }}">
    <link rel="stylesheet" href="{{ mix('css/ForgetPassword.css') }}">
    <link rel="stylesheet" href="{{ mix('css/CheckEmail.css') }}">
    <link rel="stylesheet" href="{{ mix('css/OTPVerification.css') }}">
@endpush

@section('content')
    <div class="group-888">
        <div class="group-1133 container-xs">
            <div class="group-1130">
                <div class="frame-13">
                    <div class="group-1129">
                        <div class="group-1128">
                            <h2 class="ai-powered-resume ui heading size-heading8xl slide-in-left">
                                <span class="ai-powered-resume-span"> AI-Powered</span>
                                <span class="ai-powered-resume-span-1"> &nbsp;</span>
                                <span class="ai-powered-resume-span-2"> Resume Ranking</span>
                                <span class="ai-powered-resume-span-1"> &nbsp;</span>
                                <span class="ai-powered-resume-span"> Tool!</span>
                            </h2>
                            <p class="resumeranker-ai ui text size-text3xl fade-in">
                                ResumeRanker AI is an advanced tool that analyzes resumes against job descriptions
                                using AI. It
                                delivers ranked insights, streamlines candidate evaluation, and generates detailed
                                reports for
                                efficient hiring decisions.
                            </p>
                            <div class="frame-9">
                                <button class="frame-8 ui button mb-10-sm indigo_50 size-xl fill round hover-scale"
                                    aria-controls="sign-in-modal" aria-label="Open modal">
                                    <img src="{{ asset('images/img_lock.svg') }}" alt="Lock" class="lock" />
                                    <span> Rank Your Resume</span>
                                </button>
                                <button class="frame-8 ui button ml-10-big size-xl fill round hover-scale"
                                    aria-controls="sign-up-modal" aria-label="Open modal">
                                    <img src="{{ asset('images/img_crown_1.svg') }}" alt="Crown 1" class="crown-1" />
                                    <span> Take a Subscription</span>
                                </button>
                                <!-- <img src="{{ asset('images/img_crown_1.svg') }}" alt="Crown 1" class="crown-1" />
                                                        <h3 class="take-a-subscription ui heading size-text2xl">Take a Subscription</h3> -->
                            </div>
                        </div>
                        <img src="{{ asset('images/img_22635593_6648536.png') }}" alt="22635593 6648536"
                            class="class-22635593-6648536 slide-in-right hover-scale" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.sign-in')

    @include('modals.sign-up')

    @include('modals.forget-password')

    @include('modals.otp-verification')

    @include('modals.check-email')

    @include('modals.reset-password')



@endsection

@push('scripts')
    <script>
        // Use Blade syntax to inject PHP variables properly
        const action = "{{ $action ?? '' }}";
        const showResetModal = {{ $showResetModal ? 'true' : 'false' }};
        const resetToken = "{{ $resetToken ?? '' }}";
        const resetEmail = "{{ $resetEmail ?? '' }}";
    </script>
    <script src="{{ mix('js/auth.js') }}" defer></script>
    <script src="{{ mix('js/sign-in-script.js') }}" defer></script>
    <script src="{{ mix('js/sign-up-script.js') }}" defer></script>
    <script src="{{ mix('js/otp-verification.js') }}" defer></script>
    <script src="{{ mix('js/check-action.js') }}" defer></script>
    <script src="{{ mix('js/forgot-password-script.js') }}" defer></script>
    <script src="{{ mix('js/reset-password-script.js') }}" defer></script>
    <script src="{{ mix('js/check-email-script.js') }}" defer></script>
@endpush
