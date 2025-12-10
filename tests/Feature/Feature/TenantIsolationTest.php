<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_see_other_tenants_data()
    {
        $tenantA = Tenant::create(['name' => 'Tenant A']);
        $tenantB = Tenant::create(['name' => 'Tenant B']);

        $userA = User::create([
             'tenant_id' => $tenantA->id,
             'name' => 'User A',
             'email' => 'user.a@test.com',
             'password' => bcrypt('password'),
             'role' => 'owner',
        ]);

        $buildingA = Building::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Building A',
        ]);

        $buildingB = Building::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Building B',
        ]);

        // Act
        $token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::fromUser($userA);
        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/buildings');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Building A']);
        $response->assertJsonMissing(['name' => 'Building B']);
    }
}
