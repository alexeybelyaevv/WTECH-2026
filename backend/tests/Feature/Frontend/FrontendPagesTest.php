<?php

namespace Tests\Feature\Frontend;

use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
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
}
