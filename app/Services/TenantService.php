<?php

namespace App\Services;

use App\Models\Tenant;

class TenantService
{
    public function createTenant(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function updateTenant(Tenant $tenant, array $data): Tenant
    {
        $tenant->update($data);
        return $tenant;
    }

    public function deleteTenant(Tenant $tenant): void
    {
        $tenant->delete();
    }
}
