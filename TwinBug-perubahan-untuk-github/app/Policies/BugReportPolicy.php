<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;

class BugReportPolicy
{
    /**
     * Determine whether the user can view the report.
     */
    public function view(User $user, BugReport $bugReport): bool
    {
        return $user->id === $bugReport->user_id || $user->role === UserRole::Developer;
    }
}
