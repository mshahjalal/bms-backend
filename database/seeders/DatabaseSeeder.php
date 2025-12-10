<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillCategory;
use App\Models\Building;
use App\Models\Flat;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a Super Admin (Global? Or attached to a primary tenant? Requirement implies Admin manages Tenants)
        // Usually Admin isn't scoped to a tenant, or there's a specific 'Admin Tenant'. 
        // For simplicity, Admin will match the 'Admin' role logic.
        
        // Let's create a Dummy Tenant for Admin user to exist in, or make Admin tenant_id nullable?
        // My User schema has `foreignUuid('tenant_id')->constrained()`. So User MUST belong to a Tenant.
        // I'll create a "System Tenant".
        $systemTenant = Tenant::create(['name' => 'System Admin']);
        
        User::create([
            'id' => Str::uuid(),
            'tenant_id' => $systemTenant->id,
            'name' => 'Super Admin',
            'email' => 'admin@bms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Two Tenants (Tenant A, Tenant B) for Isolation Testing
        $tenantA = Tenant::create(['name' => 'Tenant A Properties']);
        $tenantB = Tenant::create(['name' => 'Tenant B Properties']);

        // 3. Create House Owners for each
        $ownerA = User::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantA->id,
            'name' => 'Owner A',
            'email' => 'owner.a@test.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $ownerB = User::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantB->id,
            'name' => 'Owner B',
            'email' => 'owner.b@test.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // 4. Create Buildings for Tenant A
        $buildingA1 = Building::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Sunset Apartments',
            'address' => '123 Sunset Blvd',
        ]);

        // 5. Create Flat
        $flatA1 = Flat::create([
            'tenant_id' => $tenantA->id,
            'building_id' => $buildingA1->id,
            'number' => '101',
        ]);

        // 6. Create Renter and Assign
        $renterA1 = User::create([
            'id' => Str::uuid(),
            'tenant_id' => $tenantA->id,
            'name' => 'Renter One',
            'email' => 'renter.one@test.com',
            'password' => Hash::make('password'),
            'role' => 'renter',
        ]);
        
        $flatA1->renter_id = $renterA1->id;
        $flatA1->save();

        // 7. Bill Category
        $catPower = BillCategory::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Electricity',
        ]);

        // 8. Create Bill
        Bill::create([
            'tenant_id' => $tenantA->id,
            'flat_id' => $flatA1->id,
            'category_id' => $catPower->id,
            'amount' => 150.00,
            'due_date' => now()->addDays(7),
            'status' => 'unpaid',
        ]);

        // Repeat slightly for Tenant B to verify persistence
        BillCategory::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Water',
        ]);
    }
}
