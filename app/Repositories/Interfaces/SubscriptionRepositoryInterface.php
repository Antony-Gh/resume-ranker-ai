<?php

namespace App\Repositories\Interfaces;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get active subscriptions
     *
     * @return Collection
     */
    public function getActiveSubscriptions(): Collection;

    /**
     * Get active subscription for a user
     *
     * @param int $userId
     * @return Subscription|null
     */
    public function getActiveSubscriptionForUser(int $userId): ?Subscription;

    /**
     * Cancel a subscription
     *
     * @param int $id
     * @return bool
     */
    public function cancelSubscription(int $id): bool;

    /**
     * Get subscriptions expiring soon (within specified days)
     *
     * @param int $days
     * @return Collection
     */
    public function getSubscriptionsExpiringSoon(int $days = 7): Collection;
}
