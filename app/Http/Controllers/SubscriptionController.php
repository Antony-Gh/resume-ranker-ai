<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Assuming this is your Billable User model
use Illuminate\Support\Facades\Log; // For logging errors
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Exceptions\PaymentActionRequired; // Although often handled by IncompletePayment in recent Cashier versions
use Stripe\Exception\CardException; // Stripe's base card error
use Stripe\Exception\InvalidRequestException; // For things like invalid plan ID
use Exception; // General fallback

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Middleware for auth and verified email is applied at route level (web.php)
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $subscription = null;
        $hasActiveSubscription = false;
        $paymentMethods = null;
        $defaultPaymentMethod = null;
        $intent = null;

        if ($user) {
            $subscription = $user->subscription('default');
            $hasActiveSubscription = $user->subscribed('default');
            if ($user->hasStripeId()) { // Check if user is a Stripe customer
                try {
                    $paymentMethods = $user->paymentMethods();
                    $defaultPaymentMethod = $user->defaultPaymentMethod();
                } catch (Exception $e) {
                    Log::error("Error fetching payment methods for user {$user->id}: " . $e->getMessage());
                    // Optionally, pass an error to the view:
                    // session()->flash('error_payment_methods', 'Could not load your saved payment methods at this time.');
                }
            }
            try {
                $intent = $user->createSetupIntent(); // For adding new payment methods
            } catch (Exception $e) {
                 Log::error("Error creating SetupIntent for user {$user->id}: " . $e->getMessage());
                 // session()->flash('error_setup_intent', 'Could not initialize payment form.');
            }
        }

        // In a real app, you'd fetch plan details from Stripe API or your database
        // For example:
        // $plans = \Stripe\Price::all(['active' => true, 'type' => 'recurring', 'limit' => 3]);
        // Ensure you have Stripe SDK configured and exceptions handled if doing this.

        return view('subscriptions', [
            'hasActiveSubscription' => $hasActiveSubscription,
            'subscription' => $subscription,
            'paymentMethods' => $paymentMethods,
            'defaultPaymentMethod' => $defaultPaymentMethod,
            'intent' => $intent,
            // 'plans' => $plans // Pass plans to the view
        ]);
    }

    /**
     * Handles new subscription creation.
     * Route: POST /subscribe
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|max:255', // Max length for typical Stripe IDs
            'payment_method' => 'required|string|regex:/^pm_[a-zA-Z0-9_]+$/', // Basic format check for Stripe PM ID
        ]);

        /** @var User $user */
        $user = Auth::user();
        $planId = $request->input('plan');
        $paymentMethodId = $request->input('payment_method');

        try {
            // Create Stripe customer if not already one
            if (!$user->hasStripeId()) {
                $user->createAsStripeCustomer();
            }

            // Add the payment method to the customer and set as default if no default exists
            // or if you always want to update to the latest provided one.
            $user->addPaymentMethod($paymentMethodId);
            $user->updateDefaultPaymentMethod($paymentMethodId);

            // Create the new subscription
            $user->newSubscription('default', $planId)
                 ->create($paymentMethodId, [ // Cashier v13+ uses payment method ID directly here
                     'email' => $user->email,
                 ]);

            return redirect()->route('subscriptions')->with('success', 'You have successfully subscribed!');

        } catch (IncompletePayment $e) {
            Log::error("Subscription incomplete payment for user {$user->id}, plan {$planId}: " . $e->getMessage());
            if ($e->payment->requiresConfirmation()) {
                return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('subscriptions')]);
            }
            return back()->withErrors(['error' => 'The payment was incomplete. Please try again or use a different payment method.']);
        } catch (PaymentActionRequired $e) { // Maintained for compatibility, though IncompletePayment is more common now
            Log::error("Subscription payment action required for user {$user->id}, plan {$planId}: " . $e->getMessage());
             if ($e->payment->requiresConfirmation()) { // Check if payment object exists
                return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('subscriptions')]);
            }
            return back()->withErrors(['error' => 'This payment requires further action. Please follow the instructions provided.']);
        } catch (CardException $e) {
            Log::error("Subscription card error for user {$user->id}, plan {$planId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'Your card was declined: ' . $e->getMessage()]);
        } catch (InvalidRequestException $e) {
            Log::error("Subscription invalid request for user {$user->id}, plan {$planId}: " . $e->getMessage());
            // Check if the error is about an invalid plan ID
            if (str_contains($e->getMessage(), 'No such price') || str_contains($e->getMessage(), 'No such plan')) {
                 return back()->withErrors(['error' => 'The selected subscription plan is invalid. Please choose a valid plan.']);
            }
            return back()->withErrors(['error' => 'There was an issue with the subscription request. (' . $e->getMessage() . ')']);
        } catch (Exception $e) {
            Log::error("Generic subscription error for user {$user->id}, plan {$planId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred during subscription. Please try again later or contact support.']);
        }
    }

    /**
     * Handles subscription plan changes.
     * Route: PUT /subscription/change
     */
    public function update(Request $request)
    {
        $request->validate([
            'new_plan' => 'required|string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $newPlanId = $request->input('new_plan');

        try {
            if (!$user->subscribed('default')) {
                return back()->withErrors(['error' => 'You do not have an active subscription to change. Please subscribe first.']);
            }

            // Check if already subscribed to this specific price ID
            if ($user->subscription('default')->hasPrice($newPlanId)) {
                 return back()->with('status', 'You are already subscribed to this plan.');
            }

            $user->subscription('default')->swap($newPlanId);

            return redirect()->route('subscriptions')->with('success', 'Your subscription plan has been changed successfully!');
        } catch (IncompletePayment $e) {
            Log::error("Subscription update incomplete payment for user {$user->id}, new plan {$newPlanId}: " . $e->getMessage());
            if ($e->payment->requiresConfirmation()) {
                return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('subscriptions')]);
            }
            return back()->withErrors(['error' => 'The payment for the plan change was incomplete. Please try again.']);
        } catch (CardException $e) {
            Log::error("Subscription update card error for user {$user->id}, new plan {$newPlanId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'Your card was declined while updating the subscription: ' . $e->getMessage()]);
        } catch (InvalidRequestException $e) {
            Log::error("Subscription update invalid request for user {$user->id}, new plan {$newPlanId}: " . $e->getMessage());
            if (str_contains($e->getMessage(), 'No such price') || str_contains($e->getMessage(), 'No such plan')) {
                 return back()->withErrors(['error' => 'The selected new subscription plan is invalid. Please choose a valid plan.']);
            }
            return back()->withErrors(['error' => 'There was an issue with the plan change request. (' . $e->getMessage() . ')']);
        } catch (Exception $e) {
            Log::error("Generic subscription update error for user {$user->id}, new plan {$newPlanId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred while changing your plan. Please try again later or contact support.']);
        }
    }

    /**
     * Handles subscription cancellation.
     * Route: DELETE /subscription/cancel
     */
    public function destroy()
    {
        /** @var User $user */
        $user = Auth::user();

        try {
            if ($user->subscribed('default')) {
                $user->subscription('default')->cancel();
                return redirect()->route('subscriptions')->with('success', 'Your subscription has been cancelled. It will remain active until the end of your current billing period.');
            }
            return redirect()->route('subscriptions')->with('status', 'You do not have an active subscription to cancel.');
        } catch (Exception $e) {
            Log::error("Subscription cancellation error for user {$user->id}: " . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred while canceling your subscription. Please try again later or contact support.']);
        }
    }
}
