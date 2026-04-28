<?php

namespace App\Policies;

use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamResultPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ExamResult $examResult): bool
    {
        if ($user->role === 'superadmin') {
            return true;
        }

        if ($user->role === 'admin') {
            return $user->classroom_id === $examResult->user->classroom_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ExamResult $examResult): bool
    {
        if ($user->role === 'superadmin') {
            return true;
        }

        if ($user->role === 'admin') {
            return $user->classroom_id === $examResult->user->classroom_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExamResult $examResult): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExamResult $examResult): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExamResult $examResult): bool
    {
        return $user->role === 'superadmin';
    }
}
