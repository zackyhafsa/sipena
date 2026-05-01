<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->role === 'admin' && $user->school_id === $model->school_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin']);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->role === 'superadmin') return true;
        return $user->role === 'admin' && $model->role === 'student' && $user->school_id === $model->school_id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->role === 'superadmin') return false; // Mencegah penghapusan superadmin
        if ($user->role === 'superadmin') return true;
        return $user->role === 'admin' && $model->role === 'student' && $user->school_id === $model->school_id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->role === 'superadmin';
    }

    public function forceDelete(User $user, User $model): bool
    {
        if ($model->role === 'superadmin') return false; // Mencegah penghapusan superadmin
        return $user->role === 'superadmin';
    }
}
