document.addEventListener("DOMContentLoaded", function () {
    // Form elements


    const resendButton = document.getElementById('resend-email-button');

    const emailInput = document.getElementById('email-check');

    const successMessageCheck = document.getElementById('success-message-check');

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

    // Button state management
    function setButtonLoading(isLoading) {
        const button = document.getElementById('resend-email-button');
        const buttonText = document.getElementById('button-text-check');
        const spinner = document.getElementById('button-spinner-check');

        if (isLoading) {
            buttonText.style.display = 'none';
            spinner.style.display = 'flex';
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

    // Handle server errors
    function handleServerError(error) {
        console.error("Error:", error);

        let errorMessage = ERROR_MESSAGES.server.unexpected; // Default error message

        if (error.isAxiosError) {
            if (error.response) {
                if (error.response.status === 422 && error.response.data.errors) {
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
            const input = document.getElementById(`${field}-sign-up`);
            if (input) {
                const errorMessage = Array.isArray(errors[field])
                    ? errors[field][0]
                    : errors[field];

                authManager.showError(input, errorMessage);
            }
        });
    }

    resendButton.addEventListener("click", async function (e) {
        e.preventDefault();
        // Send request
        try {
            setButtonLoading(true);
            // Send request using axios
            const response = await axios.post(`${authManager.baseUrl}/forgot-password`, {
                email: localStorage.getItem('user_email_forgot'),
            });

            console.log(response);

            if (response.data.success) {
                // window.location.href = response.data.redirect;
                // localStorage.removeItem("user_email_forgot");
                authManager.showSuccess(successMessageCheck, ERROR_MESSAGES.success.resent);
                setButtonLoading(false);
                return;
            }

            if (response.data.errors) {
                handleValidationErrors(response.data.errors);
            } else {
                handleServerError(new Error(response.data.message || ERROR_MESSAGES.email.server));
            }

        } catch (error) {
            handleServerError(error);
        } finally {
            setButtonLoading(false);
        }
    });

    // Setup social login and modal navigation
    authManager.setupSocialLogin();
    authManager.setupModalNavigation();
}); 