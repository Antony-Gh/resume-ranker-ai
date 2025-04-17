document.addEventListener("DOMContentLoaded", function () {
    // Form elements
    const forgotPasswordForm = document.getElementById('forgot-password-form');
    const emailInput = document.getElementById('email-forgot');

    const forgotPasswordButton = forgotPasswordForm.querySelector('button[type="submit"]');
    const dialogSuccessMessage = forgotPasswordForm.querySelector('.dialog-success-message');


    // Modal elements
    const forgotPasswordModal = document.getElementById('forgot-password-modal');

    // Constants for error messages
    const ERROR_MESSAGES = {
        email: {
            required: "Email is required",
            invalid: "Please enter a valid email address",
            server: "Registration failed. Please try again."
        },
        server: {
            tooManyAttempts: "Too many registration attempts. Please try again later.",
            serverError: "Server error. Please try again later.",
            unexpected: "An unexpected error occurred. Please try again."
        },
        success: {
            login: "Successfully sent password reset email",
            resent: "Successfully resent password reset email",
            otp: "OTP Verification Code was sent successfully",
        }
    };

    // Initialize modal handling
    authManager.handleModal(forgotPasswordModal);

    // Input validation
    const validators = {
        email: (email) => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
    };

    // Form validation
    function validateForm() {
        let isValid = true;
        authManager.clearErrors();

        // Email validation
        if (!emailInput.value) {
            authManager.showError(emailInput, ERROR_MESSAGES.email.required);
            isValid = false;
        } else if (!validators.email(emailInput.value)) {
            authManager.showError(emailInput, ERROR_MESSAGES.email.invalid);
            isValid = false;
        }

        return isValid;
    }

    // Remove error message when user types
    const inputs = [emailInput];
    inputs.forEach(input => {
        input.addEventListener("change keydown paste input", () => {
            authManager.clearError(input);
        });
    });

    // Handle server response
    async function handleServerResponse(response) {
        const data = response.data.data;
        const user = data.user;
        // console.log(response.data);
        if (response.data.success) {
            // window.location.href = response.data.redirect;
            authManager.showSuccess(dialogSuccessMessage, ERROR_MESSAGES.success.login);

            setTimeout(() => {
                // Close all other modals first
                authManager.closeAllDialogs();

                window.authManager.modals.checkEmail?.open();
            }, 1500);
            return;
        }

        if (response.data.errors) {
            handleValidationErrors(response.data.errors);
        } else {
            authManager.showError(emailInput, response.data.message || ERROR_MESSAGES.email.server);
        }
    }

    // Handle server errors
    function handleServerError(error) {
        console.error("Error:", error);

        let errorMessage = ERROR_MESSAGES.server.unexpected; // Default error message

        if (error.isAxiosError) {
            if (error.response) {
                if (error.response.status === 422 && error.response.data.errors) {
                    console.log("Errors: ", error.response.data.errors)
                    handleValidationErrors(error.response.data.errors);
                    return;
                }

                // Use server message if available
                errorMessage = error.response.data.message || errorMessage;
            } else {
                errorMessage = "Network error. Please check your internet connection.";
            }
        } else {
            if (error.errors) {
                handleValidationErrors(error.errors);
                return;
            }

            errorMessage = error.message || errorMessage;
        }

        authManager.showError(emailInput, errorMessage);
    }

    // Handle server validation errors
    function handleValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(`${field}-forgot`);
            if (input) {
                const errorMessage = Array.isArray(errors[field])
                    ? errors[field][0]
                    : errors[field];

                authManager.showError(input, errorMessage);
            }
        });
    }


    // Form submission
    forgotPasswordForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        localStorage.setItem('user_email_forgot', emailInput.value);

        // Show loading state
        authManager.setButtonLoading(forgotPasswordButton, true);

        // Send request
        try {
            // Prepare form data
            const formData = new FormData(this);
            const formDataObj = Object.fromEntries(formData.entries());

            // Send request using axios
            const response = await axios.post(`${authManager.baseUrl}/forgot-password`, formDataObj);

            await handleServerResponse(response);
        } catch (error) {
            handleServerError(error);
        } finally {
            authManager.setButtonLoading(forgotPasswordButton, false);
        }
    });

    // Setup social login and modal navigation
    authManager.setupSocialLogin();
    authManager.setupModalNavigation();
}); 