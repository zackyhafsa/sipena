<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'superadmin';
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'superadmin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }

    public function restore(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }
}
