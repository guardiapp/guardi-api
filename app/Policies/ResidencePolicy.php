<?php

namespace App\Policies;

use App\Models\Residence;
use App\Models\User;

class ResidencePolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->type, ['Admin', 'Manager']);
    }

    public function view(User $user, Residence $residence)
    {
        return $user->type === 'Admin' || ($user->type === 'Manager' && $residence->user_id === $user->id);
    }

    public function create(User $user)
    {
        return $user->type === 'Admin';
    }

    public function update(User $user, Residence $residence)
    {
        return $user->type === 'Admin' || ($user->type === 'Manager' && $residence->user_id === $user->id);
    }

    public function delete(User $user, Residence $residence)
    {
        return $user->type === 'Admin';
    }
}
