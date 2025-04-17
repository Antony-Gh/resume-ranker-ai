<!-- Check Email Modal Dialog -->
<dialog id="check-email-modal" aria-label="Check Email Modal" aria-modal="true" class="modal-dialog">
    <!-- Close Button -->
    <button class="close-button" aria-controls="check-email-modal" aria-label="Close modal" type="button">
        <img src="{{ asset('images/img_close_icon.svg') }}" alt="Close" class="close-icon" width="24"
            height="24" />
    </button>

    <!-- Modal Content -->
    <div class="modal-container">
        <div class="modal-group container-xs">
            <!-- Email Icon Section -->
            <div class="email-icon-section">
                <img src="{{ asset('images/img_email.svg') }}" alt="Email" class="email-icon" width="80"
                    height="80" />

                <!-- Divider -->
                <div class="divider" role="separator" aria-hidden="true"></div>

                <input id="email-check" type="hidden" name="email" value="">
                <div id="email-check-error" class="floating-error" role="alert" aria-live="polite"></div>
                        

            </div>



            <!-- Header -->
            <div class="text-container">
                <h1 class="sign-in ui heading size-heading4xl">Check your Email</h1>
                <h2 class="welcome-back-please ui heading size-text2xl">
                    We have sent password recovery instructions to your email.
                </h2>
            </div>



            <!-- Continue Button -->
            <button aria-controls="check-email-modal" aria-label="Close modal" type="button" class="submit-button hover-scale ui button blue_900 size-3xl fill">
                <span class="button-text">Continue</span>
            </button>

            <div class="dialog-success-message" id="success-message-check">Success</div>
            <!-- Footer Text -->
            <h6 class="don-t-have-an-account ui heading size-textxl">
                <span class="don-t-have-an-account--span">Didn't receive the email?&nbsp;</span>
                <button type="button" class="don-t-have-an-account--span-1 hover-scale"
                    id="resend-email-button">
                    <span class="button-text" id="button-text-check">Resend</span>
                    
                    <span class="button-spinner" id="button-spinner-check" style="display: none;" aria-hidden="true">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>

                </button>
            </h6>
        </div>
    </div>
</dialog>
