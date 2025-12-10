<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function view(User $user, Bill $bill): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $bill->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function update(User $user, Bill $bill): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $bill->tenant_id;
    }

    public function delete(User $user, Bill $bill): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $bill->tenant_id;
    }
}
