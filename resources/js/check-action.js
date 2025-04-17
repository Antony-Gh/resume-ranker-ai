document.addEventListener("DOMContentLoaded", async function () {
    // Get URL parameters
    // const urlParams = new URLSearchParams(window.location.search);
    // const actionFromUrl = urlParams.get("action"); // Extract 'action' parameter

    // Use the action from window if it exists, otherwise fallback to action from URL
    // const action = window.action || actionFromUrl; // Get action from window or URL

    // Action from window
    // const action = window.action;

    // const action = actionFromUrl;

    async function sendVerificationOTPKK() {
        try {
            const user = JSON.parse(localStorage.getItem('user')); // Retrieve user data from localStorage

            if (!user || !user.token) {
                throw new Error("User is not authenticated.");
            }

            var formDataObj = {
                email: user.email // Use stored email if available
            };

            // Send request using axios
            const response = await axios.post(`${authManager.baseUrl}/send-otp`, formDataObj, {
                headers: {
                    Authorization: `Bearer ${user.token}` // Include authentication token
                }
            });

            return response.data;
        } catch (error) {
            console.error("OTP sending error:", error);
            throw new Error(error.response?.data?.message || 'Failed to send verification OTP');
        }
    }

    if (action && action !== "regular") {
        if (action === "signin") {
            window.authManager.modals.signin?.open();
        } else if (action === "signup") {
            window.authManager.modals.signup?.open();
        } else if (action === "forgot") {
            window.authManager.modals.forgotPassword?.open();
        } else if (action === "check") {
            window.authManager.modals.checkEmail?.open();
        } else if (action === "verify") {
            try {
                const user = JSON.parse(localStorage.getItem('user')); // Check user authentication

                if (user && user.token) { // Ensure user is authenticated
                    const otpResponse = await sendVerificationOTPKK();
                    if (otpResponse.success) {
                        // ✅ Dispatch custom event when OTP is sent
                        document.dispatchEvent(new CustomEvent("otpSent", {
                            detail: { email: localStorage.getItem('user_email') }
                        }));

                        window.authManager.modals.verifyOTP?.open();
                    } else {
                        throw new Error('Failed to send verification OTP');
                    }
                } else {
                    // console.log("User is not authenticated. Redirecting to login.");
                    window.authManager.modals.signin?.open();
                }
            } catch (error) {
                console.log("Error: ", error);
            }
        } else if (action === "reset") {
            window.authManager.modals.resetPassword?.open();
        }
    }
});
