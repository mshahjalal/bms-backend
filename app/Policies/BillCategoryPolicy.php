<?php

namespace App\Policies;

use App\Models\BillCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BillCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function view(User $user, BillCategory $category): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $category->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'owner' && $user->tenant_id !== null;
    }

    public function update(User $user, BillCategory $category): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $category->tenant_id;
    }

    public function delete(User $user, BillCategory $category): bool
    {
        return $user->role === 'owner' && $user->tenant_id === $category->tenant_id;
    }
}
