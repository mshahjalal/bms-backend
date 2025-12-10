<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseOwnerRequest;
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

    public function store(StoreHouseOwnerRequest $request)
    {
        Gate::authorize('admin');

        $user = $this->userService->createUser($request->validated(), 'owner');

        return response()->json($user, 201);
    }
}
