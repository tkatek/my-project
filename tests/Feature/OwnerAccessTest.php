<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerAccessTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create(['role' => 'owner']);
    }

    private function customer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_customer_is_forbidden_from_dashboard(): void
    {
        $this->actingAs($this->customer())
            ->get('/dashboard')
            ->assertForbidden();
    }

    public function test_customer_is_forbidden_from_admin_pages(): void
    {
        $user = $this->customer();

        $this->actingAs($user)->get('/admin/orders')->assertForbidden();
        $this->actingAs($user)->get('/admin/packages')->assertForbidden();
        $this->actingAs($user)->get('/admin/messages')->assertForbidden();
        $this->actingAs($user)->get('/dashboard/report')->assertForbidden();
    }

    public function test_owner_can_open_dashboard_and_admin_pages(): void
    {
        $user = $this->owner();

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/admin/orders')->assertOk();
        $this->actingAs($user)->get('/admin/packages')->assertOk();
        $this->actingAs($user)->get('/admin/messages')->assertOk();
    }

    public function test_new_registration_defaults_to_customer_role(): void
    {
        $this->post('/register', [
            'name' => 'New Person',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_customer_registration_redirects_home_not_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Person',
            'email' => 'new2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
    }

    public function test_owner_login_redirects_to_dashboard(): void
    {
        $user = $this->owner();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_customer_login_redirects_home(): void
    {
        $user = $this->customer();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
    }
}
