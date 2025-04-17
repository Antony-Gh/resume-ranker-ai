<!-- Modal Dialog -->
<dialog id="otp-verification-modal" aria-label="OTP Verification Modal" aria-modal="true" class="modal-dialog">
    <!-- Close Button -->
    <button class="close-button" aria-controls="otp-verification-modal" aria-label="Close modal" type="button">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" width="24"
            height="24" />
    </button>

    <!-- Modal Content -->
    <div class="modal-container">
        <div class="modal-group container-xs">
            <!-- Header -->
            <h1 class="sign-in ui heading size-heading4xl">OTP Verification</h1>
            <h2 class="welcome-back-please ui heading size-text2xl">
                Please check your email to see the 6-digit verification code.
            </h2>

            <!-- Divider -->
            <div class="divider" role="separator" aria-hidden="true"></div>

            <!-- OTP Form -->
            <form id="otp-verification-form" class="sign-in-form" method="POST" action="{{ route('verify.otp') }}"
                novalidate>
                {{-- @csrf --}}
                <div class="sign-in-group">
                    <div class="input-container">
                        <label for="otp-value" class="ui heading size-textxl">Enter OTP</label>
                        <div class="otp-input-container">
                            <div class="otp-input-group">
                                @for ($i = 0; $i < 6; $i++)
                                    <input type="text" class="otp-input ui input white_a700_01 size-lg fill round"
                                        maxlength="1" pattern="[0-9]" inputmode="numeric"
                                        aria-label="OTP digit {{ $i + 1 }}" required />
                                @endfor
                            </div>
                            <input type="hidden" name="otp" id="otp-value" />
                            <div id="otp-value-error" class="floating-error" role="alert" aria-live="polite">
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-button hover-scale ui button blue_900 size-3xl fill hover-scale">
                    <span class="button-text">Verify</span>
                    <span class="button-spinner" style="display: none;" aria-hidden="true">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </button>

                <div class="dialog-success-message">Success</div>
                <div class="resend-code-container">
                    <div class="resend-code-group">
                        <h4 class="resend-code ui heading size-text2xl">
                            Didn't receive the code?&nbsp;
                        </h4>
                        <h5 class="timer ui heading size-text2xl">05:00</h5>
                    </div>
                </div>
            </form>
        </div>
    </div>
</dialog>
