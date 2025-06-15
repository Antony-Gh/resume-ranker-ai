<?php

namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    /**
     * SubscriptionRepository constructor.
     *
     * @param Subscription $model
     */
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active subscriptions with caching
     *
     * @return Collection
     */
    public function getActiveSubscriptions(): Collection
    {
        $cacheKey = $this->getCacheKey('getActiveSubscriptions');

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->model->where('status', 'active')
                ->with('user') // Eager load user data
                ->get();
        });
    }

    /**
     * Get active subscription for a user with caching
     *
     * @param int $userId
     * @return Subscription|null
     */
    public function getActiveSubscriptionForUser(int $userId): ?Subscription
    {
        $cacheKey = $this->getCacheKey('getActiveSubscriptionForUser', ['userId' => $userId]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($userId) {
            return $this->model->where('user_id', $userId)
                ->where('status', 'active')
                ->first();
        });
    }

    /**
     * Cancel a subscription
     *
     * @param int $id
     * @return bool
     */
    public function cancelSubscription(int $id): bool
    {
        $subscription = $this->find($id);

        if (!$subscription) {
            return false;
        }

        $result = $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
        ]);

        $this->clearCache();

        return $result;
    }

    /**
     * Get subscriptions expiring soon (within specified days) with caching
     *
     * @param int $days
     * @return Collection
     */
    public function getSubscriptionsExpiringSoon(int $days = 7): Collection
    {
        $cacheKey = $this->getCacheKey('getSubscriptionsExpiringSoon', ['days' => $days]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($days) {
            $expiryDate = Carbon::now()->addDays($days);

            return $this->model->where('status', 'active')
                ->where('expires_at', '<=', $expiryDate)
                ->with('user') // Eager load user data
                ->get();
        });
    }
}
