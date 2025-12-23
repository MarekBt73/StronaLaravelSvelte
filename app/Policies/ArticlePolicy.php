<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageBlog();
    }

    public function view(User $user, Article $article): bool
    {
        return $user->canManageBlog();
    }

    public function create(User $user): bool
    {
        return $user->canManageBlog();
    }

    public function update(User $user, Article $article): bool
    {
        return $user->canManageBlog();
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->canManageBlog();
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->canManageBlog();
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->canManageBlog();
    }
}
