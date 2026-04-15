<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JsonAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_me_endpoint_returns_guest_state_for_unauthenticated_requests(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response
            ->assertOk()
            ->assertJson([
                'authenticated' => false,
                'user' => null,
            ]);
    }

    public function test_new_users_can_register_via_json_api(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Frontend User',
            'email' => 'frontend@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('authenticated', true)
            ->assertJsonPath('user.email', 'frontend@example.com')
            ->assertJsonPath('redirect_to', '/index.html');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'frontend@example.com',
        ]);
    }

    public function test_existing_users_can_login_via_json_api(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('authenticated', true)
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('redirect_to', '/index.html');

        $this->assertAuthenticated();
    }
}
