<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage_users']);
        Role::create(['name' => 'admin']);

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo('manage_users');
    }
    /**
     * A basic feature test example.
     */
    public function test_admin_can_login()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token']
            ]);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/users/create', [
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@test.com'
        ]);
    }

    public function test_manager_cannot_access_admin_routes()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        Sanctum::actingAs($manager);

        $response = $this->getJson('/api/audit-logs');

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/users/' . $user->id . '/delete');

        $response->assertStatus(200);

        $this->assertSoftDeleted('users', [
            'id' => $user->id
        ]);
    }

    public function test_user_cannot_delete_self()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/users/' . $admin->id . '/delete');

        $response->assertStatus(403);
    }
}
