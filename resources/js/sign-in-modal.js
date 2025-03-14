/**
         * Handles the dialog behavior
         */
function handleModal(/** @type {HTMLDialogElement} */ target) {
    const backdrop = document.getElementById('modal-backdrop');

    function open() {
        target.style.display = 'block'; // Show modal
        backdrop.style.display = 'block'; // Show backdrop
        document.body.classList.add('modal-open'); // Prevent scrolling
    }

    function close() {
        target.style.display = 'none'; // Hide modal
        backdrop.style.display = 'none'; // Hide backdrop
        document.body.classList.remove('modal-open'); // Allow scrolling
    }

    function toggle() {
        if (target.style.display === 'block') {
            close();
        } else {
            open();
        }
    }

    const buttons = /** @type {HTMLElement} */ (document.querySelectorAll(`[aria-controls="${target.id}"]`));
    const closebutton = /** @type {HTMLElement} */ (document.querySelectorAll(`[aria-controls="${target.id}"]`));

    for (const button of buttons) {
        button.addEventListener("click", () => {
            toggle();
        });
    }

    // Close modal when clicking outside
    backdrop.addEventListener("click", () => {
        close();
    });

    // Close modal when pressing Escape key
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") close();
    });
}

/**
 * Hydrate modals(s)
 */
document.addEventListener("DOMContentLoaded", () => {
    const modals = /** @type {HTMLElement[]} */ (document.querySelectorAll('[aria-modal="true"]'));
    for (const modal of modals) handleModal(modal);

    // Add event listener to switch between Signup and Signin modals
    const openSigninModal = document.getElementById('open-signin-modal');
    const openSignupModal = document.getElementById('open-signup-modal');
    const signinModal = document.getElementById('modal-dialog-sign-in'); // Signup modal
    const signupModal = document.getElementById('modal-dialog-sign-up'); // Signin modal

    console.log(openSigninModal);
    console.log(openSignupModal);
    console.log(signinModal);
    console.log(signupModal);

    if (openSigninModal && openSignupModal && signupModal && signinModal) {
        console.log("Done");
        openSigninModal.addEventListener('click', () => {
            console.log("Click");
            // Close Signup modal
            signupModal.style.display = 'none';
            // Open Signin modal
            signinModal.style.display = 'block';
            document.getElementById('modal-backdrop').style.display = 'block';
            document.body.classList.add('modal-open');
        });

        openSignupModal.addEventListener('click', () => {
            // Close Signin modal
            signinModal.style.display = 'none';
            // Open Signup modal
            signupModal.style.display = 'block';
            document.getElementById('modal-backdrop').style.display = 'block';
            document.body.classList.add('modal-open');
        });
    }
});