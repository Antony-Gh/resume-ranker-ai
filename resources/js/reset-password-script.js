/**
 * Reset Password form handler
 * Manages form validation, submission, and error handling for password reset
 */
document.addEventListener("DOMContentLoaded", function () {
    // ======================
    // ELEMENT SELECTORS
    // ======================
    const resetPasswordForm = document.getElementById('reset-password-form');
    const newPasswordInput = document.getElementById('password-reset');
    const confirmPasswordInput = document.getElementById('password_confirmation-reset');
    const resetPasswordButton = resetPasswordForm?.querySelector('button[type="submit"]');
    const togglePasswordButton = document.getElementById('toggle-password-reset');
    const togglePasswordConfirmButton = document.getElementById('toggle-password_confirmation-reset');
    const dialogSuccessMessage = resetPasswordForm?.querySelector('.dialog-success-message');
    const resetPasswordModal = document.getElementById('reset-password-modal');

    // Check if required elements exist
    if (!resetPasswordForm || !resetPasswordButton) {
        console.error('Reset password form elements not found');
        return;
    }

    // ======================
    // CONSTANTS & CONFIGURATION
    // ======================
    const ERROR_MESSAGES = {
        password: {
            required: "Password is required",
            invalid: "Password must be at least 8 characters long and contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character",
            server: "Password reset failed. Please try again."
        },
        password_confirmation: {
            required: "Please confirm your password",
            mismatch: "Passwords do not match"
        },
        server: {
            tokenInvalid: "Password reset token is invalid or has expired",
            tooManyAttempts: "Too many reset attempts. Please try again later.",
            serverError: "Server error. Please try again later.",
            unexpected: "An unexpected error occurred. Please try again."
        },
        success: "Password reset successful. You can now log in with your new password."
    };

    // ======================
    // INITIALIZATION
    // ======================
    try {
        // Initialize modal handling
        if (resetPasswordModal) {
            authManager.handleModal(resetPasswordModal);
        }

        // Setup password toggles if elements exist
        if (togglePasswordButton && newPasswordInput) {
            authManager.setupPasswordToggle(togglePasswordButton, newPasswordInput);
        }
        if (togglePasswordConfirmButton && confirmPasswordInput) {
            authManager.setupPasswordToggle(togglePasswordConfirmButton, confirmPasswordInput);
        }
    } catch (error) {
        console.error('Initialization error:', error);
    }

    // ======================
    // VALIDATION FUNCTIONS
    // ======================
    const validators = {
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
     * Validates the reset password form
     * @returns {boolean} True if form is valid, false otherwise
     */
    function validateForm() {
        let isValid = true;
        authManager.clearErrors();

        // Validate new password
        if (!newPasswordInput?.value) {
            authManager.showError(newPasswordInput, ERROR_MESSAGES.password.required);
            isValid = false;
        } else if (!validators.password(newPasswordInput.value)) {
            authManager.showError(newPasswordInput, ERROR_MESSAGES.password.invalid);
            isValid = false;
        }

        // Validate password confirmation
        if (!confirmPasswordInput?.value) {
            authManager.showError(confirmPasswordInput, ERROR_MESSAGES.password_confirmation.required);
            isValid = false;
        } else if (!validators.passwordConfirmation(newPasswordInput.value, confirmPasswordInput.value)) {
            authManager.showError(confirmPasswordInput, ERROR_MESSAGES.password_confirmation.mismatch);
            isValid = false;
        }

        return isValid;
    }

    // ======================
    // EVENT HANDLERS
    // ======================
    // Real-time validation on input
    const inputs = [newPasswordInput, confirmPasswordInput].filter(Boolean);
    inputs.forEach(input => {
        input.addEventListener("input", () => {
            authManager.clearError(input);

            // Special handling for password fields
            if (input === newPasswordInput && confirmPasswordInput?.value) {
                if (!validators.passwordConfirmation(newPasswordInput.value, confirmPasswordInput.value)) {
                    authManager.showError(confirmPasswordInput, ERROR_MESSAGES.password_confirmation.mismatch);
                } else {
                    authManager.clearError(confirmPasswordInput);
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
            const input = document.getElementById(`${field}-reset`);
            const errorMessage = Array.isArray(errors[field])
                ? errors[field][0]
                : errors[field];
            if (input) {
                authManager.showError(input, errorMessage);
            } else {
                authManager.showError(confirmPasswordInput, errorMessage);
            }
        });
    }

    // ======================
    // SERVER RESPONSE HANDLING
    // ======================
    /**
     * Processes successful password reset response
     * @param {Object} response - Axios response object
     */
    async function handleServerResponse(response) {
        if (!response?.data?.success) {
            throw new Error(response.data?.message || 'Password reset failed');
        }

        // Show success message
        authManager.showSuccess(dialogSuccessMessage, ERROR_MESSAGES.success);

        // Close modal after delay
        setTimeout(() => {
            authManager.closeAllDialogs();

            // Redirect to login page if available
            if (window.authManager?.modals?.signin) {
                window.authManager.modals.signin.open();
            }
        }, 1500);
    }

    /**
     * Handles server errors during password reset
     * @param {Error} error - Error object
     */
    function handleServerError(error) {
        console.error("Password reset error:", error);

        let errorMessage = ERROR_MESSAGES.server.unexpected;
        let targetInput = newPasswordInput;

        if (error.response) {
            const { status, data } = error.response;

            if (status === 422 && data.errors) {
                handleValidationErrors(data.errors);
                return;
            }

            errorMessage = data?.message ||
                (status === 400 ? ERROR_MESSAGES.server.tokenInvalid :
                    status === 401 ? ERROR_MESSAGES.password.server :
                        status === 429 ? ERROR_MESSAGES.server.tooManyAttempts :
                            status === 500 ? ERROR_MESSAGES.server.serverError :
                                ERROR_MESSAGES.server.unexpected);

            targetInput = errorMessage.toLowerCase().includes('token') ?
                null : newPasswordInput;
        } else if (error.request) {
            errorMessage = ERROR_MESSAGES.server.serverError;
        } else {
            errorMessage = error.message || ERROR_MESSAGES.server.unexpected;
        }

        if (targetInput) {
            authManager.showError(targetInput, errorMessage);
        } else {
            // For token errors, show as general form error since there's no token input field
            authManager.showFormError(resetPasswordForm, errorMessage);
        }
    }

    // ======================
    // FORM SUBMISSION
    // ======================
    resetPasswordForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        try {
            authManager.setButtonLoading(resetPasswordButton, true);

            // Prepare form data
            const formData = new FormData(resetPasswordForm);
            const formDataObj = Object.fromEntries(formData.entries());

            // Extract token from URL if not in form
            if (!formDataObj.token) {
                const urlParams = new URLSearchParams(window.location.search);
                formDataObj.token = urlParams.get('token') || '';
            }

            // Send reset request
            const response = await axios.post(`${authManager.baseUrl}/reset-password`, formDataObj);
            await handleServerResponse(response);
        } catch (error) {
            handleServerError(error);
        } finally {
            authManager.setButtonLoading(resetPasswordButton, false);
        }
    });
});