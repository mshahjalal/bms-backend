<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_get_token()
    {
        $tenant = Tenant::create(['name' => 'Test Tenant']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }

    public function test_house_owner_cannot_access_admin_routes()
    {
        $tenant = Tenant::create(['name' => 'Test Tenant']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/tenants'); // Admin only route

        $response->assertStatus(403);
    }
}
