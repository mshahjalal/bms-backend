<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data, string $role): User
    {
        return User::create([
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);
    }
    
    // Additional methods if needed for updates/deletes in future
}
