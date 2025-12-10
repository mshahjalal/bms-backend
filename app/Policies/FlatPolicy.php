<?php

namespace App\Policies;

use App\Models\Flat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FlatPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function view(User $user, Flat $flat): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $flat->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function update(User $user, Flat $flat): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $flat->tenant_id;
    }

    public function delete(User $user, Flat $flat): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $flat->tenant_id;
    }
}
