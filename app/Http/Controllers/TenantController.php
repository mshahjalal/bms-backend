<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TenantController extends Controller
{
    public function __construct(protected TenantService $tenantService)
    {
    }

    public function index()
    {
        Gate::authorize('admin');
        return response()->json(Tenant::all());
    }

    public function store(StoreTenantRequest $request)
    {
        Gate::authorize('admin');

        $tenant = $this->tenantService->createTenant($request->validated());

        return response()->json($tenant, 201);
    }

    public function show(Tenant $tenant)
    {
        Gate::authorize('admin');
        return response()->json($tenant);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        Gate::authorize('admin');

        $tenant = $this->tenantService->updateTenant($tenant, $request->validated());

        return response()->json($tenant);
    }

    public function destroy(Tenant $tenant)
    {
        Gate::authorize('admin');
        $this->tenantService->deleteTenant($tenant);
        return response()->json(null, 204);
    }
}
