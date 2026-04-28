<?php

namespace App\Helpers;

class SchoolContext
{
    /**
     * Get the active school_id for the current user.
     * - For admin: always their own school_id.
     * - For superadmin: the session-selected school, or null if "all schools".
     */
    public static function getActiveSchoolId(): ?int
    {
        $user = auth()->user();

        if (!$user) return null;

        if ($user->role === 'admin') {
            return $user->school_id;
        }

        if ($user->role === 'superadmin') {
            return session('active_school_id') ? (int) session('active_school_id') : null;
        }

        // For students
        return $user->school_id;
    }

    /**
     * Check if there is an active school context set.
     */
    public static function hasActiveSchool(): bool
    {
        return self::getActiveSchoolId() !== null;
    }
}
