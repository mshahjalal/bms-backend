<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseOwnerRequest;
use App\Http\Requests\UpdateHouseOwnerRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HouseOwnerController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function list()
    {
        Gate::authorize('admin');

        $users = User::where('role', 'owner')->paginate(20);

        return response()->json($users);
    }   

    public function store(StoreHouseOwnerRequest $request)
    {
        Gate::authorize('admin');

        $user = $this->userService->createUser($request->validated(), 'owner');

        return response()->json($user, 201);
    }

    public function update(UpdateHouseOwnerRequest $request, $id)
    {
        Gate::authorize('admin');

        $user = User::where('role', 'owner')->findOrFail($id);

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // Allow updating tenant_id if provided? Assuming yes for admin.
        if ($request->filled('tenant_id')) {
            $user->tenant_id = $request->tenant_id;
        }

        $user->save();

        return response()->json($user);
    }
}
