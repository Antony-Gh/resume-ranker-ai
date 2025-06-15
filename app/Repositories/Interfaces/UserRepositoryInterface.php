<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Get users by role
     *
     * @param string $role
     * @return Collection
     */
    public function findByRole(string $role): Collection;

    /**
     * Get active users
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection;

    /**
     * Get users with active subscriptions
     *
     * @return Collection
     */
    public function getUsersWithActiveSubscriptions(): Collection;
}
