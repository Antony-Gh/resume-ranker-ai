document.addEventListener('DOMContentLoaded', function () {
    const otpModal = document.getElementById('otp-verification-modal');
    const otpForm = document.getElementById('otp-verification-form');
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpValueInput = document.getElementById('otp-value');
    const submitButton = otpForm.querySelector('button[type="submit"]');
    const resendCodeGroup = document.querySelector('.resend-code-group');
    const timerElement = document.querySelector('.timer');
    const dialogSuccessMessage = otpForm.querySelector('.dialog-success-message');

    const closeButton = otpModal.querySelector('.close-button');

    let timeLeft = 300; // 5 minutes in seconds
    let timerInterval;

    /**
     * Updates hidden input field with the complete OTP.
     */
    function updateOTPValue() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValueInput.value = otp;
    }

    // Start timer
    function startTimer() {
        // ✅ Disable resending while the timer is running
        resendCodeGroup.classList.add("is-disabled");
        resendCodeGroup.classList.remove("is-active");

        timerInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);

                // ✅ Enable resend option
                resendCodeGroup.classList.remove("is-disabled");
                resendCodeGroup.classList.add("is-active");
                timerElement.textContent = "Resend";
                return; // ✅ Stop decrementing after reaching 0
            }

            timeLeft--;
        }, 1000);
    }

    /**
     * Displays an error message.
     * @param {string} message
     */
    function showError(message) {
        authManager.showError(otpValueInput, message);
    }

    async function handleFormSubmission() {
        const otp = otpValueInput.value;

        if (otp.length !== 6) {
            showError('Please enter a valid 6-digit OTP');
            return;
        }

        // Show loading state
        authManager.setButtonLoading(submitButton, true);

        try {
            const response = await axios.post('/verify-otp', {
                email: localStorage.getItem('user_email'),
                otp: otp
            });

            console.log(response);

            const data = response.data;

            if (data.success) {
                // Handle successful verification
                authManager.showSuccess(dialogSuccessMessage, data.message);
                setTimeout(() => {
                    // Close the modal
                    authManager.closeAllDialogs();
                    // Redirect to dashboard
                    window.location.href = '/dashboard';
                }, 500);
            } else {
                showError(data.message || 'Verification failed. Please try again.');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        } catch (error) {
            showError(error.response?.data?.message || 'An error occurred. Try again.');
            console.error('Error:', error);
        } finally {
            // Reset button state
            authManager.setButtonLoading(submitButton, false);
        }
    }

    // Handle OTP input
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", function (e) {
            const value = e.target.value;

            // ✅ Allow only numeric input (digits 0-9)
            if (!/^\d*$/.test(value)) {
                e.target.value = ''; // Clear invalid input
                return;
            }

            // ✅ Update hidden input with full OTP
            updateOTPValue();

            // ✅ Move to the next input if a digit is entered
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // ✅ Submit form when last digit is entered
            if (index === otpInputs.length - 1 && value) {
                handleFormSubmission();
            }
        });

        input.addEventListener('keydown', function (e) {
            // ✅ Handle Backspace key to move back & clear previous input
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = ''; // Clear previous input
            }
        });
    });



    // Handle form submission
    otpForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        handleFormSubmission();
    });

    // Handle resend code with Axios
    resendCodeGroup.addEventListener('click', async function () {
        if (timeLeft > 0 || resendCodeGroup.classList.contains('is-loading')) return;

        resendCodeGroup.classList.add('is-loading');

        try {
            const response = await axios.post(`${authManager.baseUrl}/send-otp`, {
                email: localStorage.getItem('user_email')
            });

            if (response.data.success) {
                // ✅ Dispatch custom event when OTP is sent
                document.dispatchEvent(new CustomEvent("otpSent", {
                    detail: { email: localStorage.getItem('user_email') }
                }));
            } else {
                showError(response.data.message || 'Failed to send OTP. Try again.');
            }
        } catch (error) {
            const errorMsg = error.response?.data?.message ||
                error.message ||
                'An error occurred. Please try again.';
            showError(errorMsg);
        } finally {
            resendCodeGroup.classList.remove('is-loading');
        }
    });

    // Handle close button
    closeButton.addEventListener('click', function () {
        window.authManager.modals.verifyOTP?.close();
        // Reset form
        otpForm.reset();
        otpInputs.forEach(input => input.value = '');
        otpValueInput.value = '';
        // Reset timer
        clearInterval(timerInterval);
        timeLeft = 300;
        startTimer();
    });

    // Handle modal close event
    otpModal.addEventListener('close', function () {
        // Reset form
        otpForm.reset();
        otpInputs.forEach(input => input.value = '');
        otpValueInput.value = '';
        // Reset timer
        clearInterval(timerInterval);
        timeLeft = 300;
        startTimer();
    });


    // Start initial timer
    startTimer();

    document.addEventListener("otpSent", function (event) {

        let msg = "OTP sent successfully for: " + event.detail.email;

        console.log(msg);

        authManager.showSuccess(dialogSuccessMessage, msg);

        // ✅ Example: Show a success message
        // alert("A new OTP has been sent to your email: " + event.detail.email);

        // Reset form
        otpForm.reset();
        otpInputs.forEach(input => input.value = '');
        otpValueInput.value = '';
        // Reset timer
        clearInterval(timerInterval);
        timeLeft = 300;
        startTimer();

        // ✅ Example: Auto-focus the first OTP input field
        otpInputs[0]?.focus();
    });
});


