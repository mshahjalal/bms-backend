<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRenterRequest;
use App\Http\Requests\StoreRenterRequest;
use App\Models\User;
use App\Models\Flat;
use App\Services\FlatService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class RenterController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected FlatService $flatService
    ) {}

    public function index()
    {
        Gate::authorize('admin');
        return response()->json(User::where('role', 'renter')->get());
    }

    public function store(StoreRenterRequest $request)
    {
        Gate::authorize('admin');

        $user = $this->userService->createUser($request->validated(), 'renter');

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        Gate::authorize('admin');
        if ($user->role !== 'renter') {
            abort(404);
        }
        return response()->json($user);
    }

    public function assign(AssignRenterRequest $request)
    {
        Gate::authorize('admin');

        $validated = $request->validated();

        $flat = Flat::withoutGlobalScopes()->findOrFail($validated['flat_id']); // Admin can access all
        $renter = User::findOrFail($validated['renter_id']);

        if ($renter->role !== 'renter') {
            return response()->json(['error' => 'User is not a renter'], 400);
        }

        if ($flat->tenant_id !== $renter->tenant_id) {
             return response()->json(['error' => 'Renter and Flat must belong to the same tenant'], 400);
        }

        $this->flatService->assignRenter($flat, $renter->id);

        return response()->json(['message' => 'Renter assigned successfully', 'flat' => $flat]);
    }
}
