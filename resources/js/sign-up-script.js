/**
 * Sign-up form handler
 * Manages form validation, submission, and error handling for user registration
 */
document.addEventListener("DOMContentLoaded", function () {
    // ======================
    // ELEMENT SELECTORS
    // ======================
    const signUpForm = document.getElementById('sign-up-form');
    const nameInput = document.getElementById('name-sign-up');
    const emailInput = document.getElementById('email-sign-up');
    const passwordInput = document.getElementById('password-sign-up');
    const passwordConfirmationInput = document.getElementById('password_confirmation-sign-up');
    const signUpButton = signUpForm?.querySelector('button[type="submit"]');
    const togglePasswordButton = document.getElementById('toggle-password-sign-up');
    const togglePasswordConfirmationButton = document.getElementById('toggle-password-confirmation-sign-up');
    const dialogSuccessMessage = signUpForm?.querySelector('.dialog-success-message');
    const signupModal = document.getElementById('sign-up-modal');

    // Check if required elements exist
    if (!signUpForm || !signUpButton) {
        console.error('Sign-up form elements not found');
        return;
    }

    // ======================
    // CONSTANTS & CONFIGURATION
    // ======================
    const ERROR_MESSAGES = {
        name: {
            required: "Name is required",
            invalid: "Name must be between 2 and 50 characters"
        },
        email: {
            required: "Email is required",
            invalid: "Please enter a valid email address",
            server: "Registration failed. Please try again."
        },
        password: {
            required: "Password is required",
            invalid: "Password must be at least 8 characters long and contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character"
        },
        password_confirmation: {
            required: "Please confirm your password",
            mismatch: "Passwords do not match"
        },
        server: {
            tooManyAttempts: "Too many registration attempts. Please try again later.",
            serverError: "Server error. Please try again later.",
            unexpected: "An unexpected error occurred. Please try again."
        },
        success: {
            login: "Successfully signed up!",
            otp: "OTP Verification Code was sent successfully",
        }
    };

    // ======================
    // INITIALIZATION
    // ======================
    try {
        // Initialize modal handling
        if (signupModal) {
            authManager.handleModal(signupModal);
        }

        // Setup password toggles if elements exist
        if (togglePasswordButton && passwordInput) {
            authManager.setupPasswordToggle(togglePasswordButton, passwordInput);
        }
        if (togglePasswordConfirmationButton && passwordConfirmationInput) {
            authManager.setupPasswordToggle(togglePasswordConfirmationButton, passwordConfirmationInput);
        }
    } catch (error) {
        console.error('Initialization error:', error);
    }

    // ======================
    // VALIDATION FUNCTIONS
    // ======================
    const validators = {
        name: (name) => {
            if (typeof name !== 'string') return false;
            const trimmed = name.trim();
            return trimmed.length > 2 && trimmed.length <= 50;
        },
        email: (email) => {
            if (typeof email !== 'string') return false;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email.trim());
        },
        password: (password) => {
            if (typeof password !== 'string') return false;
            // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            return passwordRegex.test(password);
        },
        passwordConfirmation: (password, confirmation) => {
            return password === confirmation;
        }
    };

    // ======================
    // FORM VALIDATION
    // ======================
    /**
     * Validates the entire sign-up form
     * @returns {boolean} True if form is valid, false otherwise
     */
    function validateForm() {
        let isValid = true;
        authManager.clearErrors();

        // Validate name
        if (!nameInput?.value?.trim()) {
            authManager.showError(nameInput, ERROR_MESSAGES.name.required);
            isValid = false;
        } else if (!validators.name(nameInput.value)) {
            authManager.showError(nameInput, ERROR_MESSAGES.name.invalid);
            isValid = false;
        }

        // Validate email
        if (!emailInput?.value?.trim()) {
            authManager.showError(emailInput, ERROR_MESSAGES.email.required);
            isValid = false;
        } else if (!validators.email(emailInput.value)) {
            authManager.showError(emailInput, ERROR_MESSAGES.email.invalid);
            isValid = false;
        }

        // Validate password
        if (!passwordInput?.value) {
            authManager.showError(passwordInput, ERROR_MESSAGES.password.required);
            isValid = false;
        } else if (!validators.password(passwordInput.value)) {
            authManager.showError(passwordInput, ERROR_MESSAGES.password.invalid);
            isValid = false;
        }

        // Validate password confirmation
        if (!passwordConfirmationInput?.value) {
            authManager.showError(passwordConfirmationInput, ERROR_MESSAGES.password_confirmation.required);
            isValid = false;
        } else if (!validators.passwordConfirmation(passwordInput.value, passwordConfirmationInput.value)) {
            authManager.showError(passwordConfirmationInput, ERROR_MESSAGES.password_confirmation.mismatch);
            isValid = false;
        }

        return isValid;
    }

    // ======================
    // EVENT HANDLERS
    // ======================
    // Real-time validation on input
    const inputs = [nameInput, emailInput, passwordInput, passwordConfirmationInput].filter(Boolean);
    inputs.forEach(input => {
        input.addEventListener("input", () => {
            authManager.clearError(input);
            
            // Special handling for password fields
            if (input === passwordInput && passwordConfirmationInput?.value) {
                if (!validators.passwordConfirmation(passwordInput.value, passwordConfirmationInput.value)) {
                    authManager.showError(passwordConfirmationInput, ERROR_MESSAGES.password_confirmation.mismatch);
                } else {
                    authManager.clearError(passwordConfirmationInput);
                }
            }
        });
    });

    // ======================
    // ERROR HANDLING
    // ======================
    /**
     * Handles server-side validation errors
     * @param {Object} errors - Error object from server response
     */
    function handleValidationErrors(errors) {
        if (!errors || typeof errors !== 'object') return;

        Object.keys(errors).forEach(field => {
            const input = document.getElementById(`${field}-sign-up`);
            if (input) {
                const errorMessage = Array.isArray(errors[field])
                    ? errors[field][0]
                    : errors[field];
                authManager.showError(input, errorMessage);
            }
        });
    }

    // ======================
    // OTP HANDLING
    // ======================
    /**
     * Sends verification OTP to the registered email
     * @returns {Promise<Object>} Response data
     */
    async function sendVerificationOTP() {
        try {
            authManager.setButtonLoading(signUpButton, true);
            
            const userEmail = localStorage.getItem('user_email');
            if (!userEmail) {
                throw new Error('User email not found');
            }

            const response = await axios.post(`${authManager.baseUrl}/send-otp`, {
                email: userEmail
            });

            if (response.data?.success) {
                authManager.showSuccess(dialogSuccessMessage, ERROR_MESSAGES.success.otp);
                return response.data;
            }
            throw new Error(response.data?.message || 'Failed to send OTP');
        } catch (error) {
            console.error("OTP sending error:", error);
            throw error;
        } finally {
            authManager.setButtonLoading(signUpButton, false);
        }
    }

    // ======================
    // SERVER RESPONSE HANDLING
    // ======================
    /**
     * Processes successful registration response
     * @param {Object} response - Axios response object
     */
    async function handleServerResponse(response) {
        if (!response?.data?.success) {
            throw new Error(response.data?.message || 'Registration failed');
        }

        const {csrf_token, token, user} = response.data.data;
        if (!user?.email) {
            throw new Error('Invalid user data received');
        }

        // Store user data
        localStorage.setItem('token', token || '');
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('user_email', user.email);

        // Update CSRF token if provided
        if (csrf_token) {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfMeta.setAttribute('content', csrf_token);
            }
        }

        // Show success message
        authManager.showSuccess(dialogSuccessMessage, ERROR_MESSAGES.success.login);

        // Handle email verification
        const isEmailVerified = !!user.email_verified_at;
        if (!isEmailVerified) {
            try {
                await sendVerificationOTP();
                document.dispatchEvent(new CustomEvent("otpSent", {
                    detail: { email: user.email }
                }));
                
                // Show OTP verification modal if available
                if (window.authManager?.modals?.verifyOTP) {
                    window.authManager.modals.verifyOTP.open();
                }
            } catch (error) {
                authManager.showError(emailInput, error.message);
            }
        } else {
            window.location.href = '/dashboard';
        }
    }

    /**
     * Handles server errors during registration
     * @param {Error} error - Error object
     */
    function handleServerError(error) {
        console.error("Registration error:", error);
        
        let errorMessage = ERROR_MESSAGES.server.unexpected;
        let targetInput = emailInput;

        if (error.response) {
            const { status, data } = error.response;
            
            if (status === 422 && data.errors) {
                handleValidationErrors(data.errors);
                return;
            }

            errorMessage = data?.message || 
                (status === 401 ? ERROR_MESSAGES.password.server :
                status === 429 ? ERROR_MESSAGES.server.tooManyAttempts :
                status === 500 ? ERROR_MESSAGES.server.serverError :
                ERROR_MESSAGES.server.unexpected);

            targetInput = errorMessage.toLowerCase().includes('password') ? 
                passwordInput : emailInput;
        } else if (error.request) {
            errorMessage = ERROR_MESSAGES.server.serverError;
        } else {
            errorMessage = error.message || ERROR_MESSAGES.server.unexpected;
        }

        if (targetInput) {
            authManager.showError(targetInput, errorMessage);
        } else {
            alert(errorMessage); // Fallback error display
        }
    }

    // ======================
    // FORM SUBMISSION
    // ======================
    signUpForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        try {
            authManager.setButtonLoading(signUpButton, true);
            
            // Prepare form data
            const formData = new FormData(signUpForm);
            const formDataObj = Object.fromEntries(formData.entries());

            // Clean data
            Object.keys(formDataObj).forEach(key => {
                if (typeof formDataObj[key] === 'string') {
                    formDataObj[key] = formDataObj[key].trim();
                }
            });

            // Send registration request
            const response = await axios.post(`${authManager.baseUrl}/register`, formDataObj);
            await handleServerResponse(response);
        } catch (error) {
            handleServerError(error);
        } finally {
            authManager.setButtonLoading(signUpButton, false);
        }
    });

    // ======================
    // SOCIAL LOGIN & MODAL NAVIGATION
    // ======================
    try {
        authManager.setupSocialLogin();
        authManager.setupModalNavigation();
    } catch (error) {
        console.error('Social login or modal navigation setup failed:', error);
    }
});