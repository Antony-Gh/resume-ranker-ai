<!-- Modal Dialog -->
<dialog id="forgot-password-modal" aria-label="Forgot Password Modal" aria-modal="true" class="modal-dialog">
    <!-- Close Button -->
    <button class="close-button" aria-controls="forgot-password-modal" aria-label="Close modal" type="button">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" width="24" height="24" />
    </button>

    <!-- Modal Content -->
    <div class="modal-container">
        <div class="modal-group container-xs">
            <!-- Header -->
            <h1 class="sign-in ui heading size-heading4xl">Forgot Password</h1>
            <h2 class="welcome-back-please ui heading size-text2xl">Enter your email to reset your password</h2>

            <!-- Divider -->
            <div class="divider" role="separator" aria-hidden="true"></div>

            <!-- Forgot Password Form -->
            <form id="forgot-password-form" class="sign-in-form" method="POST" action="{{ route('password.email') }}" novalidate>
                {{-- @csrf --}}

                <!-- Form Fields -->
                <div class="sign-in-group">
                    <div class="input-container">
                        <label for="email-forgot" class="ui heading size-textxl">Email address</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="email-forgot" name="email" type="email" placeholder="Enter your email"
                                autocomplete="email username" autocapitalize="none" required aria-required="true"
                                aria-invalid="false" aria-describedby="email-error" value="{{ $resetEmail }}" />
                            <img src="{{ asset('images/img_basic_mail.svg') }}" alt="" class="basic--mail"
                                aria-hidden="true" width="24" height="24" />
                            <div id="email-forgot-error" class="floating-error" role="alert" aria-live="polite"></div>
                        </div>
                    </div>
                </div>

                    <button type="submit" class="submit-button hover-scale ui button blue_900 size-3xl fill">
                        <span class="button-text">Reset Password</span>
                        <span class="button-spinner" style="display: none;" aria-hidden="true">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                    </button>

                <div class="social-login-container">
                    <div class="or-group">
                        <div class="or-divider"></div>
                        <h5 class="or ui heading size-text2xl">OR</h5>
                        <div class="or-divider"></div>
                    </div>
                    <div class="social-login-group">
                        <a class="mb-10-sm hover-scale" href="/#" target="_blank">
                            <button type="button"
                                class="social-login-button ui button white_a700_01 size-3xl fill round">
                                <img src="{{ asset('images/img_image_here.png') }}" alt="Google" class="image-here" />
                                <span> Google</span>
                            </button>
                        </a>
                        <a class="hover-scale" href="/#" target="_blank">
                            <button type="button"
                                class="social-login-button ui button white_a700_01 size-3xl fill round">
                                <div class="left-icon-wrapper">
                                    <img src="{{ asset('images/img_facebook.svg') }}" alt="Facebook" class="facebook" />
                                </div>
                                <span> Facebook</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="dialog-success-message">Success</div>
                <h6 class="don-t-have-an-account ui heading size-textxl">
                    <span class="don-t-have-an-account--span">Remember your password?&nbsp;</span>
                    <button type="button" class="don-t-have-an-account--span-1 hover-scale"
                        aria-controls="sign-in-modal" aria-label="Open modal">Sign In</button>
                </h6>
            </form>
        </div>
    </div>
</dialog>