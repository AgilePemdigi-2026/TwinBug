<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DetectionResult;
use App\Models\User;

class DetectionResultPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Developer;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DetectionResult $detectionResult): bool
    {
        if ($user->role !== UserRole::Developer) {
            return false;
        }

        return $detectionResult->bugReport->application_id === $user->application_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DetectionResult $detectionResult): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DetectionResult $detectionResult): bool
    {
        return false;
    }
}
