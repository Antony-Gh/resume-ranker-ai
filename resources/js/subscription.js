/**
 * Subscription management JavaScript
 */

import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    initSubscriptionForms();
    initSubscriptionCancellation();
    initPlanSelection();
});

/**
 * Initialize subscription forms
 */
function initSubscriptionForms() {
    const subscriptionForm = document.getElementById('subscription-form');
    if (!subscriptionForm) return;

    subscriptionForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitButton = subscriptionForm.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            // Get form data
            const formData = new FormData(subscriptionForm);
            const data = Object.fromEntries(formData.entries());

            // Submit form
            const response = await axios.post(subscriptionForm.action, data);

            if (response.data.success) {
                window.location.href = response.data.redirect;
            } else {
                showError('An error occurred while processing your subscription.');
            }
        } catch (error) {
            console.error('Subscription error:', error);

            if (error.response && error.response.data && error.response.data.errors) {
                // Display validation errors
                Object.entries(error.response.data.errors).forEach(([field, messages]) => {
                    const errorElement = document.getElementById(`${field}-error`);
                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }
                });
            } else {
                showError('An error occurred while processing your subscription.');
            }
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    });
}

/**
 * Initialize subscription cancellation
 */
function initSubscriptionCancellation() {
    const cancelButtons = document.querySelectorAll('.cancel-subscription-btn');
    if (!cancelButtons.length) return;

    cancelButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!confirm('Are you sure you want to cancel this subscription?')) {
                return;
            }

            const subscriptionId = button.dataset.id;
            const url = `/subscriptions/${subscriptionId}/cancel`;

            try {
                button.disabled = true;
                button.textContent = 'Cancelling...';

                const response = await axios.post(url);

                if (response.data.success) {
                    window.location.reload();
                } else {
                    showError('An error occurred while cancelling your subscription.');
                }
            } catch (error) {
                console.error('Cancellation error:', error);
                showError('An error occurred while cancelling your subscription.');
            }
        });
    });
}

/**
 * Initialize plan selection
 */
function initPlanSelection() {
    const planCards = document.querySelectorAll('.plan-card');
    if (!planCards.length) return;

    planCards.forEach(card => {
        card.addEventListener('click', () => {
            // Remove active class from all cards
            planCards.forEach(c => c.classList.remove('plan-active'));

            // Add active class to selected card
            card.classList.add('plan-active');

            // Update hidden input
            const planInput = document.getElementById('plan_id');
            if (planInput) {
                planInput.value = card.dataset.planId;
            }

            // Update price display
            updatePriceDisplay(card.dataset.planId);
        });
    });

    // Initialize with first plan selected
    if (planCards.length > 0) {
        planCards[0].click();
    }

    // Handle duration change
    const durationSelect = document.getElementById('duration');
    if (durationSelect) {
        durationSelect.addEventListener('change', () => {
            const activePlan = document.querySelector('.plan-active');
            if (activePlan) {
                updatePriceDisplay(activePlan.dataset.planId);
            }
        });
    }
}

/**
 * Update price display based on selected plan and duration
 */
function updatePriceDisplay(planId) {
    const durationSelect = document.getElementById('duration');
    const priceDisplay = document.getElementById('price-display');
    const amountInput = document.getElementById('amount');

    if (!durationSelect || !priceDisplay || !amountInput || !planId) return;

    const duration = parseInt(durationSelect.value);

    // Get plan data from data attributes
    const planData = JSON.parse(document.getElementById('plan-data').textContent);
    const plan = planData[planId];

    if (!plan) return;

    // Calculate price based on duration
    let price;
    if (duration >= 12) {
        // Apply yearly pricing
        price = (plan.price.yearly / 12) * duration;
    } else {
        // Apply monthly pricing
        price = plan.price.monthly * duration;
    }

    // Update price display and hidden input
    priceDisplay.textContent = `$${price.toFixed(2)}`;
    amountInput.value = price.toFixed(2);
}

/**
 * Show error message
 */
function showError(message) {
    const errorContainer = document.getElementById('error-container');
    if (!errorContainer) return;

    errorContainer.textContent = message;
    errorContainer.classList.remove('hidden');

    // Scroll to error
    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
