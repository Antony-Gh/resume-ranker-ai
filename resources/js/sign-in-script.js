document.addEventListener("DOMContentLoaded", function () {
    // Form elements
    const signInForm = document.getElementById('sign-in-form');
    const emailInput = document.getElementById('email-sign-in');
    const passwordInput = document.getElementById('password-sign-in');
    const loginButton = signInForm.querySelector('button[type="submit"]');
    const togglePasswordButton = document.getElementById('toggle-password-sign-in');
    const dialogSuccessMessage = signInForm.querySelector('.dialog-success-message');

    // Modal elements
    const signinModal = document.getElementById('sign-in-modal');

    // Constants for error messages
    const ERROR_MESSAGES = {
        email: {
            required: "Email is required",
            invalid: "Please enter a valid email address",
            server: "Invalid email or password. Please try again."
        },
        password: {
            required: "Password is required",
            minLength: "Password must be at least 8 characters long",
            server: "Invalid email or password. Please try again."
        },
        server: {
            tooManyAttempts: "Too many login attempts. Please try again later.",
            serverError: "Server error. Please try again later.",
            unexpected: "An unexpected error occurred. Please try again."
        },
        success: {
            login: "Successfully Logged in!",
            otp: "OTP Verification Code was sent successfully",
        }
    };

    // Initialize modal handling
    authManager.handleModal(signinModal);

    // Setup password toggle
    authManager.setupPasswordToggle(togglePasswordButton, passwordInput);

    // Input validation
    const validators = {
        email: (email) => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        password: (password) => password.length >= 8
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

        // Password validation
        if (!passwordInput.value) {
            authManager.showError(passwordInput, ERROR_MESSAGES.password.required);
            isValid = false;
        } else if (!validators.password(passwordInput.value)) {
            authManager.showError(passwordInput, ERROR_MESSAGES.password.minLength);
            isValid = false;
        }

        return isValid;
    }

    // Remove error message when user types
    const inputs = [emailInput, passwordInput];
    inputs.forEach(input => {
        input.addEventListener("change keydown paste input", () => authManager.clearError(input));
    });

    // Handle server validation errors
    function handleValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(`${field}-sign-in`);
            if (input) {
                const errorMessage = Array.isArray(errors[field])
                    ? errors[field][0]
                    : errors[field];
                authManager.showError(input, errorMessage);
            }
        });
    }

    async function sendVerificationOTP() {
        try {
            // Show loading state
            authManager.setButtonLoading(loginButton, true);

            var formDataObj = {
                email: localStorage.getItem('user_email')
            };

            // Send request using axios
            const response = await axios.post(`${authManager.baseUrl}/send-otp`, formDataObj);

            return response.data;
        } catch (error) {
            console.error("OTP sending error:", error);
            throw new Error(error.response?.data?.message || 'Failed to send verification OTP');
        } finally {
            authManager.setButtonLoading(loginButton, false);
        }
    }

    // Handle server response
    async function handleServerResponse(response) {
        const data = response.data.data;
        const user = data.user;
        // console.log(response.data);
        if (response.data.success) {
            if (data.token) {
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(user));
                localStorage.setItem('user_email', user.email);

                document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);

                authManager.showSuccess(dialogSuccessMessage, ERROR_MESSAGES.success.login);

                // Check email verification status
                const isEmailVerified = user.email_verified_at !== null;

                // console.log(user.email_verified_at);
                // console.log(isEmailVerified);

                if (!isEmailVerified) {
                    try {
                        // Send OTP
                        const otpResponse = await sendVerificationOTP();
                        if (otpResponse.success) {
                            // ✅ Dispatch custom event when OTP is sent
                            document.dispatchEvent(new CustomEvent("otpSent", {
                                detail: { email: localStorage.getItem('user_email') }
                            }));

                            // Show OTP verification modal
                            window.authManager.modals.verifyOTP?.open();
                        } else {
                            throw new Error('Failed to send verification OTP');
                        }
                    } catch (error) {
                        authManager.showError(emailInput, error.message);
                    }
                } else {
                    if (localStorage.getItem("user_email_forgot") !== null) {
                        localStorage.removeItem("user_email_forgot");
                    }    
                    window.location.href = '/dashboard';
                }
            }
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

        if (error.response) {
            // The request was made and the server responded with a status code
            const { status, data } = error.response;

            if (status === 422 && data.errors) {
                handleValidationErrors(data.errors);
                return;
            }

            const errorMessage = data.message ||
                (status === 401 ? ERROR_MESSAGES.password.server :
                    status === 429 ? ERROR_MESSAGES.server.tooManyAttempts :
                        status === 500 ? ERROR_MESSAGES.server.serverError :
                            ERROR_MESSAGES.server.unexpected);

            const targetInput = errorMessage.toLowerCase().includes('email') ||
                errorMessage.toLowerCase().includes('validation')
                ? emailInput
                : passwordInput;

            authManager.showError(targetInput, errorMessage);
        } else if (error.request) {
            // The request was made but no response was received
            authManager.showError(emailInput, ERROR_MESSAGES.server.serverError);
        } else {
            // Something happened in setting up the request
            authManager.showError(emailInput, error.message || ERROR_MESSAGES.server.unexpected);
        }
    }

    // Form submission
    signInForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        // Show loading state
        authManager.setButtonLoading(loginButton, true);

        try {
            // Prepare form data
            const formData = new FormData(this);
            const formDataObj = Object.fromEntries(formData.entries());

            // Send request using axios
            const response = await axios.post(`${authManager.baseUrl}/login`, formDataObj);

            await handleServerResponse(response);
        } catch (error) {
            handleServerError(error);
        } finally {
            authManager.setButtonLoading(loginButton, false);
        }
    });

    // Setup social login and modal navigation
    authManager.setupSocialLogin();
    authManager.setupModalNavigation();
});