<?php

namespace Tests\Feature\Frontend;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_storefront(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/index.html');
    }

    public function test_storefront_page_can_be_rendered(): void
    {
        $response = $this->get('/index.html');

        $response
            ->assertOk()
            ->assertSee('Digital Game Hub');
    }

    public function test_store_data_asset_can_be_rendered(): void
    {
        $response = $this->get('/store_data.js');

        $response
            ->assertOk()
            ->assertSee('window.StoreMvp');
    }

    public function test_auth_ui_asset_can_be_rendered(): void
    {
        $response = $this->get('/auth_ui.js');

        $response
            ->assertOk()
            ->assertSee('Logout');
    }

    public function test_admin_pages_require_authentication(): void
    {
        $response = $this->get('/admin_manage.html');

        $response->assertRedirect('/login');
    }

    public function test_admin_pages_reject_non_admin_users(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
        ]);

        $response = $this->actingAs($user)->get('/admin_manage.html');

        $response->assertForbidden();
    }

    public function test_admin_pages_can_be_rendered_for_admins(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin_manage.html');

        $response
            ->assertOk()
            ->assertSee('Product management');
    }
}
