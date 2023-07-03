<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Invitation $model): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Invitation $model): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Invitation $model): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Invitation $model): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Invitation $model): bool
    {
        return $user->hasRole('admin');
    }
}
