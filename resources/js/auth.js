// Common authentication functionality
class AuthManager {
    constructor() {
        this.baseUrl = document.querySelector('meta[name="base-url"]').content;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        this.errorTimeouts = {}; // Initialize error timeouts object

        // Define all modals in a configuration object
        this.modalConfig = {
            signin: 'sign-in-modal',
            signup: 'sign-up-modal',
            forgotPassword: 'forgot-password-modal',
            verifyOTP: 'otp-verification-modal',
            checkEmail: 'check-email-modal',
            resetPassword: 'reset-password-modal'
            // Add more modals here as needed
        };
        this.modals = {};
        this.initializeModals();
    }

    // Modal initialization
    initializeModals() {
        // Get modal elements
        // const signinModal = document.getElementById('sign-in-modal');
        // const signupModal = document.getElementById('sign-up-modal');
        // const forgotPasswordModal = document.getElementById('forgot-password-modal');
        // const verifyEmailModal = document.getElementById('otp-verification-modal');
        // const checkEmailModal = document.getElementById('check-email-modal');
        // const backdrop = document.getElementById('modal-backdrop');

        // console.log(signinModal);
        // console.log(signupModal);
        // console.log(forgotPasswordModal);
        // console.log(verifyEmailModal);

        // Initialize modal handlers
        // if (signinModal) this.modals.signin = this.handleModal(signinModal);
        // if (signupModal) this.modals.signup = this.handleModal(signupModal);
        // if (forgotPasswordModal) this.modals.forgotPassword = this.handleModal(forgotPasswordModal);
        // if (verifyEmailModal) this.modals.verifyOTP = this.handleModal(verifyEmailModal);
        // if (checkEmailModal) this.modals.checkEmail = this.handleModal(checkEmailModal);

        // Loop through the config and initialize each modal
        Object.entries(this.modalConfig).forEach(([key, modalId]) => {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                this.modals[key] = this.handleModal(modalElement);
            }
        });

        // console.log(this.modals);

        // Setup modal navigation
        this.setupModalNavigation();
    }

    // Modal handling
    handleModal(target) {
        const backdrop = document.getElementById('modal-backdrop');

        const open = () => {
            // Close all other modals first
            Object.values(this.modals).forEach(modal => {
                if (modal && modal !== this) {
                    modal.close();
                }
            });

            target.show();
            backdrop.style.display = 'block';
            target.style.display = 'block';
            document.body.classList.add('modal-open');
        };

        const close = () => {
            target.close();
            backdrop.style.display = 'none';
            target.style.display = 'none';
            document.body.classList.remove('modal-open');
        };

        const toggle = () => {
            if (target.open) {
                close();
            } else {
                open();
            }
        };

        // Close button handling
        const allButtons = document.querySelectorAll(`[aria-controls="${target.id}"]`);

        // Select all buttons that have an aria-controls attribute
        allButtons.forEach(button => {
            // Determine action based on aria-label
            if (button.getAttribute("aria-label")?.toLowerCase().includes("close")) {
                button.addEventListener("click", close);
            } else {
                button.addEventListener("click", open);
            }
        });

        // Backdrop click handling
        backdrop.addEventListener("click", close);

        // Escape key handling
        window.addEventListener("keydown", (e) => {
            if (e.key === "Escape") close();
        });

        return { open, close, toggle };
    }

    // Enhanced error handling
    showError(inputField, message) {
        const errorId = inputField.id + '-error';
        const errorElement = document.getElementById(errorId);
        const parentGroup = inputField.closest("div");

        if (!errorElement) return;

        // Clear any existing timeout to prevent overlap
        if (this.errorTimeouts[errorId]) {
            clearTimeout(this.errorTimeouts[errorId]);
        }

        // Set error state
        errorElement.textContent = message;
        errorElement.classList.add('show');
        errorElement.classList.remove('hide');
        inputField.setAttribute('aria-invalid', 'true');
        inputField.classList.add('error-state');
        parentGroup.classList.add('error-state');

        // Store timeout reference
        this.errorTimeouts[errorId] = setTimeout(() => {
            this.clearError(inputField);
            delete this.errorTimeouts[errorId];
        }, 3000);
    }

    clearError(inputField) {
        const errorId = inputField.id + '-error';
        const errorElement = document.getElementById(errorId);
        const parentGroup = inputField.closest("div");
        
        if (errorElement) {
            errorElement.textContent = "";
            errorElement.classList.remove('show');
            errorElement.classList.add('hide');
            inputField.setAttribute('aria-invalid', 'false');
            inputField.classList.remove('error-state');
            parentGroup.classList.remove('error-state');
            
            // Clean up timeout reference if it exists
            if (this.errorTimeouts[errorId]) {
                delete this.errorTimeouts[errorId];
            }
        }
    }

    // Success handling
    showSuccess(element, message) {
        if (element) {
            element.textContent = message;
            element.classList.add('show');
            element.classList.remove('hide');
            setTimeout(() => this.clearSuccess(element), 3000);
        }
    }

    clearSuccess(element) {
        if (element) {
            element.textContent = "";
            element.classList.remove('show');
            element.classList.add('hide');
        }
    }

    clearErrors() {
        // Clear all error messages
        document.querySelectorAll('.error-message').forEach(error => {
            error.textContent = "";
            error.classList.remove('show');
            error.classList.add('hide');
        });

        // Clear all error states
        document.querySelectorAll('input[aria-invalid="true"]').forEach(input => {
            input.setAttribute('aria-invalid', 'false');
            input.classList.remove('error-state');
            const parentGroup = input.closest("div");
            if (parentGroup) {
                parentGroup.classList.remove('error-state');
            }
            
            // Clear any pending timeouts
            const errorId = input.id + '-error';
            if (this.errorTimeouts[errorId]) {
                clearTimeout(this.errorTimeouts[errorId]);
                delete this.errorTimeouts[errorId];
            }
        });
    }

    // Button state management
    setButtonLoading(button, isLoading) {
        const buttonText = button.querySelector('.button-text');
        const spinner = button.querySelector('.button-spinner');

        if (isLoading) {
            buttonText.style.display = 'none';
            spinner.style.display = 'inline-block';
            button.disabled = true;
            button.style.opacity = '0.7';
            button.style.cursor = 'not-allowed';
        } else {
            buttonText.style.display = 'inline-block';
            spinner.style.display = 'none';
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        }
    }

    // Password visibility toggle
    setupPasswordToggle(button, input) {
        // console.log(button, input);
        button.addEventListener('click', () => {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = button.querySelector('img');
            icon.src = type === 'password'
                ? '/images/img_eye_24_hidden.svg'
                : '/images/img_eye_24_visible.svg';
        });
    }

    // Modal navigation
    setupModalNavigation() {
        // Sign up to Sign in
        document.getElementById('open-signin-modal')?.addEventListener('click', (e) => {
            e.preventDefault();
            // Close all other modals first
            this.closeAllDialogs();
            this.modals.signin?.open();
        });

        // Sign in to Sign up
        document.getElementById('open-signup-modal')?.addEventListener('click', (e) => {
            e.preventDefault();
            // Close all other modals first
            this.closeAllDialogs();
            this.modals.signup?.open();
        });

        // Sign in to Forgot Password
        document.getElementById('open-forget-password-modal')?.addEventListener('click', (e) => {
            e.preventDefault();
            // Close all other modals first
            this.closeAllDialogs();
            this.modals.forgotPassword?.open();
        });
    }

    // Social login handlers
    setupSocialLogin() {
        document.querySelectorAll('.btn-social').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const provider = button.classList.contains('btn-google') ? 'google' : 'facebook';
                this.handleSocialLogin(provider);
            });
        });
    }

    handleSocialLogin(provider) {
        // Implement social login logic here
        console.log(`${provider} login clicked`);
    }

    closeAllDialogs() {
        Object.values(this.modals).forEach(modal => {
            if (modal) {
                modal.close();
            }
        });
    }
}

// Initialize auth manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.authManager = new AuthManager();
});
