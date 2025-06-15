<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\SubscriptionCreateRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Subscriptions",
 *     description="API Endpoints for subscription management"
 * )
 */
class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionRepositoryInterface
     */
    protected SubscriptionRepositoryInterface $subscriptionRepository;

    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the subscriptions.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()->with('user')->paginate(10);

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new subscription.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $plans = config('subscription.plans');
        return view('subscriptions.create', compact('plans'));
    }

    /**
     * Store a newly created subscription in storage.
     *
     * @param SubscriptionCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubscriptionCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $validatedData = $request->validated();

            // Check if user already has an active subscription
            if ($user->hasActiveSubscription()) {
                return redirect()->back()
                    ->withErrors(['subscription' => 'You already have an active subscription'])
                    ->withInput();
            }

            // Create the subscription
            $subscription = new Subscription([
                'plan_id' => $validatedData['plan_id'],
                'status' => 'active',
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths($validatedData['duration']),
                'payment_method' => $validatedData['payment_method'],
                'payment_id' => $validatedData['payment_id'] ?? null,
                'amount' => $validatedData['amount'],
                'currency' => $validatedData['currency'] ?? 'USD',
            ]);

            $user->subscriptions()->save($subscription);

            // Log the subscription creation
            Log::channel('security')->info('Subscription created', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'plan_id' => $subscription->plan_id,
                'amount' => $subscription->amount,
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('subscriptions.show', $subscription->id)
                ->with('success', 'Subscription created successfully');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to create subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to create subscription. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified subscription.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        try {
            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                throw new ResourceNotFoundException('Subscription', $id);
            }

            // Check if the subscription belongs to the authenticated user
            if ($subscription->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                throw new ApiException('Unauthorized access', 403);
            }

            return view('subscriptions.show', compact('subscription'));
        } catch (ResourceNotFoundException $e) {
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (ApiException $e) {
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified subscription.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View | \Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        try {
            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                throw new ResourceNotFoundException('Subscription', $id);
            }

            // Check if the subscription belongs to the authenticated user
            if ($subscription->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                throw new ApiException('Unauthorized access', 403);
            }

            $plans = config('subscription.plans');

            return view('subscriptions.edit', compact('subscription', 'plans'));
        } catch (ResourceNotFoundException $e) {
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (ApiException $e) {
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified subscription in storage.
     *
     * @param SubscriptionUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SubscriptionUpdateRequest $request, int $id)
    {
        try {
            DB::beginTransaction();

            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                throw new ResourceNotFoundException('Subscription', $id);
            }

            // Check if the subscription belongs to the authenticated user
            if ($subscription->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                throw new ApiException('Unauthorized access', 403);
            }

            $validatedData = $request->validated();

            // Update the subscription
            $subscription->update([
                'plan_id' => $validatedData['plan_id'] ?? $subscription->plan_id,
                'status' => $validatedData['status'] ?? $subscription->status,
                'expires_at' => isset($validatedData['duration'])
                    ? Carbon::parse($subscription->starts_at)->addMonths($validatedData['duration'])
                    : $subscription->expires_at,
            ]);

            // Log the subscription update
            Log::channel('security')->info('Subscription updated', [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('subscriptions.show', $subscription->id)
                ->with('success', 'Subscription updated successfully');
        } catch (ResourceNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (ApiException $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to update subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'subscription_id' => $id,
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to update subscription. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Cancel the specified subscription.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(int $id)
    {
        try {
            DB::beginTransaction();

            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                throw new ResourceNotFoundException('Subscription', $id);
            }

            // Check if the subscription belongs to the authenticated user
            if ($subscription->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                throw new ApiException('Unauthorized access', 403);
            }

            // Cancel the subscription
            $this->subscriptionRepository->cancelSubscription($id);

            // Log the subscription cancellation
            Log::channel('security')->info('Subscription cancelled', [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('subscriptions.index')
                ->with('success', 'Subscription cancelled successfully');
        } catch (ResourceNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (ApiException $e) {
            DB::rollBack();
            return redirect()->route('subscriptions.index')
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Failed to cancel subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'subscription_id' => $id,
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to cancel subscription. Please try again.']);
        }
    }

    /**
     * API endpoint to get subscription details.
     *
     * @OA\Get(
     *     path="/api/subscriptions/{id}",
     *     operationId="getSubscription",
     *     tags={"Subscriptions"},
     *     summary="Get subscription details",
     *     description="Returns details of a specific subscription",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscription ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Subscription"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Subscription with identifier '1' not found")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getSubscription(Request $request, int $id): JsonResponse
    {
        try {
            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                throw new ResourceNotFoundException('Subscription', $id);
            }

            // Check if the subscription belongs to the authenticated user
            if ($subscription->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                throw new ApiException('Unauthorized access', 403);
            }

            return response()->json([
                'success' => true,
                'data' => $subscription,
            ]);
        } catch (ResourceNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (ApiException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (Throwable $e) {
            Log::error('Failed to get subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'subscription_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get subscription details',
            ], 500);
        }
    }

    /**
     * API endpoint to get active subscription for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/subscriptions/active",
     *     operationId="getActiveSubscription",
     *     tags={"Subscriptions"},
     *     summary="Get active subscription",
     *     description="Returns the active subscription for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Subscription"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No active subscription found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No active subscription found")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveSubscription(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $subscription = $this->subscriptionRepository->getActiveSubscriptionForUser($user->id);

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active subscription found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $subscription,
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to get active subscription', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get active subscription details',
            ], 500);
        }
    }
}
