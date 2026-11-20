<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin'))
            {
                return true;
            }
        return false;
    }

    public function create(User $user):bool
    {
        if($user->hasRole('admin'))
            {
                return true;
            }
        return false;
    }

    public function view(User $user, User $model)
    {
        if($user->hasRole('admin'))
            {
                return true;
            }
        if($user->id === $model->id)
            {
                return true;
            }
        return false;
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) 
            {
                return true;
            }

        return false;
    }
}

