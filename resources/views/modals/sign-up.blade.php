<!-- Modal Dialog -->
<dialog id="sign-up-modal" aria-label="Sign Up Modal" aria-modal="true" class="modal-dialog">
    <button class="close-button" aria-controls="sign-up-modal" aria-label="Close modal">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" />
    </button>
    <div class="modal-container">
        <div class="modal-group container-xs">
            <h1 class="sign-in ui heading size-heading4xl">Sign Up</h1>
            <h2 class="welcome-enter-email ui heading size-text2xl">Welcome! Enter Email to Signup</h2>
            <div class="divider"></div>
            <form id="sign-up-form" method="POST" action="{{ route('register') }}" class="sign-in-form" novalidate>
                {{-- @csrf --}}
                <div class="frame-34846">
                    <div class="input-container">
                        <label for="name-sign-up" class="ui heading size-textxl">Name</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="name-sign-up" name="name" type="text" placeholder="Enter your name"
                                required autocomplete="name" aria-required="true" aria-invalid="false" />
                            <img src="{{ asset('images/img_basic_name.svg') }}" alt="Basic / Name" class="basic--mail"
                                aria-hidden="true" />
                            <div id="name-sign-up-error" class="floating-error" role="alert" aria-live="polite"></div>
                        </div>
                    </div>
                    <div class="input-container">
                        <label for="email-sign-up" class="ui heading size-textxl">Email address</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="email-sign-up" name="email" type="email" placeholder="Enter your email"
                                required autocomplete="email" aria-required="true" aria-invalid="false" />
                            <img src="{{ asset('images/img_basic_mail.svg') }}" alt="Basic / Mail"
                                class="basic--mail" />
                            <div id="email-sign-up-error" class="floating-error" role="alert" aria-live="polite">
                            </div>
                        </div>
                    </div>
                    <div class="input-container">
                        <label for="password-sign-up" class="ui heading size-textxl">Password</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="password-sign-up" name="password" type="password"
                                placeholder="Enter your password" required autocomplete="new-password"
                                aria-required="true" aria-invalid="false" />
                            <button id="toggle-password-sign-up" type="button" class="toggle-password"
                                aria-label="Toggle password visibility" aria-controls="password" aria-pressed="false">
                                <img src="{{ asset('images/img_eye_24_hidden.svg') }}" alt=""
                                    class="password-icon" aria-hidden="true" width="24" height="24" />
                            </button>
                            <div id="password-sign-up-error" class="floating-error" role="alert" aria-live="polite">
                            </div>
                        </div>
                    </div>
                    <div class="input-container">
                        <label for="password_confirmation-sign-up" class="ui heading size-textxl">Confirm
                            Password</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="password_confirmation-sign-up" name="password_confirmation" type="password"
                                placeholder="Confirm your password" required autocomplete="new-password"
                                aria-required="true" aria-invalid="false" />
                            <button id="toggle-password-confirmation-sign-up" type="button" class="toggle-password"
                                aria-label="Toggle password confirmation visibility"
                                aria-controls="password_confirmation" aria-pressed="false">
                                <img src="{{ asset('images/img_eye_24_hidden.svg') }}" alt=""
                                    class="password-icon" aria-hidden="true" width="24" height="24" />
                            </button>
                            <div id="password_confirmation-sign-up-error" class="floating-error" role="alert"
                                aria-live="polite">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-button hover-scale ui button blue_900 size-3xl fill">
                    <span class="button-text">Sign Up</span>
                    <span class="button-spinner">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </button>

                <div class="social-login-container">
                    <div class="or-group">
                        <div class="or-divider"></div>
                        <h6 class="already-have-an ui heading size-text2xl">OR</h6>
                        <div class="or-divider"></div>
                    </div>
                    <div class="social-login-group">
                        <a href="/#" class="mb-10-sm hover-scale" target="_blank">
                            <button class="social-login-button ui button white_a700_01 size-3xl fill round">
                                <img src="{{ asset('images/img_image_here.png') }}" alt="Google"
                                    class="image-here" />
                                <span> Google</span>
                            </button>
                        </a>
                        <a href="/#" class="hover-scale" target="_blank">
                            <button class="social-login-button ui button white_a700_01 size-3xl fill round">
                                <div class="left-icon-wrapper">
                                    <img src="{{ asset('images/img_facebook.svg') }}" alt="Facebook"
                                        class="facebook" />
                                </div>
                                <span> Facebook</span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="dialog-success-message">Success</div>
                <p class="already-have-an ui heading size-textxl">
                    <span class="don-t-have-an-account--span">Already Have an account?</span>
                    <button type="button" class="don-t-have-an-account--span-1 hover-scale"
                        aria-controls="sign-in-modal" aria-label="Open modal">Sign
                        In</button>
                </p>
            </form>
        </div>
    </div>
</dialog>
