<?php

namespace App\Policies;

use App\Models\SaverUser;
use App\Models\SaverCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaverCategoryPolicy
{
    use HandlesAuthorization;

    public function view(SaverUser $user, SaverCategory $category)
    {
        return $user->id === $category->user_id;
    }

    public function create(SaverUser $user)
    {
        return true; // Any authenticated user can create categories
    }

    public function update(SaverUser $user, SaverCategory $category)
    {
        return $user->id === $category->user_id;
    }

    public function delete(SaverUser $user, SaverCategory $category)
    {
        return $user->id === $category->user_id;
    }

    public function restore(SaverUser $user, SaverCategory $category)
    {
        return $user->id === $category->user_id;
    }

    public function forceDelete(SaverUser $user, SaverCategory $category)
    {
        return $user->id === $category->user_id;
    }
}
