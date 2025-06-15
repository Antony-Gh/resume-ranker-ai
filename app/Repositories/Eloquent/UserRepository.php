<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a user by email with caching
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        $cacheKey = $this->getCacheKey('findByEmail', ['email' => $email]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($email) {
            return $this->model->where('email', $email)->first();
        });
    }

    /**
     * Get users by role with caching
     *
     * @param string $role
     * @return Collection
     */
    public function findByRole(string $role): Collection
    {
        $cacheKey = $this->getCacheKey('findByRole', ['role' => $role]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($role) {
            return $this->model->where('role', $role)->get();
        });
    }

    /**
     * Get active users with caching
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection
    {
        $cacheKey = $this->getCacheKey('getActiveUsers');

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->model->active()->get();
        });
    }

    /**
     * Get users with active subscriptions with caching and eager loading
     *
     * @return Collection
     */
    public function getUsersWithActiveSubscriptions(): Collection
    {
        $cacheKey = $this->getCacheKey('getUsersWithActiveSubscriptions');

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->model->with('activeSubscription')
                ->whereHas('subscriptions', function ($query) {
                    $query->where('status', 'active');
                })
                ->get();
        });
    }
}
