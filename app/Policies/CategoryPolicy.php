<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Category $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Category $category): bool
    {
        return (int) $user->id === (int) $category->user_id;
    }

    public function delete(User $user, Category $category): bool
    {
        return (int) $user->id === (int) $category->user_id;
    }

    public function restore(User $user, Category $category): bool
    {
        return (int) $user->id === (int) $category->user_id;
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return (int) $user->id === (int) $category->user_id;
    }
}