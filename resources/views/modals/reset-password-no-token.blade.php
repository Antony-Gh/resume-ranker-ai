<!-- Modal Dialog -->
<dialog id="reset-password-modal" aria-label="Reset Password Modal" aria-modal="true" class="modal-dialog">
    <!-- Close Button -->
    <button class="close-button" aria-controls="reset-password-modal" aria-label="Close modal" type="button">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" width="24"
            height="24" />
    </button>

    <!-- Modal Content -->
    <div class="modal-container">
        <div class="modal-group container-xs">
            <!-- Header -->
            <h1 class="sign-in ui heading size-heading4xl">Reset Password</h1>
            <h2 class="welcome-back-please ui heading size-text2xl">
                Don’t worry! It happens. Please enter the new password for your account.
            </h2>

            <!-- Divider -->
            <div class="divider" role="separator" aria-hidden="true"></div>

            <!-- Reset Password Form -->
            <form id="reset-password-form" class="sign-in-form" method="POST" action="{{ route('password.update') }}"
                novalidate>
                {{-- @csrf --}}

                <input type="hidden" name="email" value="{{ $resetEmail }}">

                <div class="sign-in-group">
                    <div class="input-container">
                        <label for="old-password-reset" class="ui heading size-textxl">Old Password</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="old-password-reset" name="password" type="password"
                                placeholder="Enter new password" required aria-required="true" />
                            <button id="toggle-password-reset" type="button" class="toggle-password"
                                aria-label="Toggle password visibility" aria-controls="old-password-reset"
                                aria-pressed="false">
                                <img src="{{ asset('images/img_eye_24_outline.svg') }}" alt="Toggle Password Visibility"
                                    class="password-icon" width="24" height="24" />
                            </button>
                            <div id="old-password-reset-error" class="floating-error" role="alert" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="input-container">
                        <label for="password-reset" class="ui heading size-textxl">New Password</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="password-reset" name="password" type="password"
                                placeholder="Enter new password" required aria-required="true" />
                            <button id="toggle-password-reset" type="button" class="toggle-password"
                                aria-label="Toggle password visibility" aria-controls="password-reset"
                                aria-pressed="false">
                                <img src="{{ asset('images/img_eye_24_outline.svg') }}" alt="Toggle Password Visibility"
                                    class="password-icon" width="24" height="24" />
                            </button>
                            <div id="password-reset-error" class="floating-error" role="alert" aria-live="polite"></div>
                        </div>
                    </div>

                    <div class="input-container">
                        <label for="password_confirmation-reset" class="ui heading size-textxl">Confirm Password</label>
                        <div class="input-field ui input white_a700_01 size-lg fill round">
                            <input id="password_confirmation-reset" name="password_confirmation" type="password"
                                placeholder="Confirm new password" required aria-required="true" />
                            <button id="toggle-password_confirmation-reset" type="button" class="toggle-password"
                                aria-label="Toggle password visibility" aria-controls="password_confirmation-reset"
                                aria-pressed="false">
                                <img src="{{ asset('images/img_eye_24_outline.svg') }}" alt="Toggle Password Visibility"
                                    class="password-icon" width="24" height="24" />
                            </button>
                            <div id="password_confirmation-reset-error" class="floating-error" role="alert" aria-live="polite"></div>
                        </div>
                    </div>
                </div>

                <div class="dialog-success-message">Success</div>

                <button type="submit" class="submit-button hover-scale ui button blue_900 size-3xl fill">
                    <span class="button-text">Sign In</span>
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
            </form>
        </div>
    </div>
</dialog>
