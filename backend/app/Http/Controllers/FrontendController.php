<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FrontendController extends Controller
{
    public const PAGES = [
        'index',
        'shop',
        'cart',
        'cart_order',
        'cart_success',
        'product_details',
        'login',
        'register',
        'admin_manage',
        'admin_new',
        'admin_edit',
    ];

    private const ASSETS = [
        'auth_ui.js' => 'application/javascript; charset=UTF-8',
        'store_data.js' => 'application/javascript; charset=UTF-8',
        'styles.css' => 'text/css; charset=UTF-8',
    ];

    public function page(string $page): BinaryFileResponse
    {
        abort_unless(in_array($page, self::PAGES, true), 404);
        $path = $this->frontendPath("{$page}.html");
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store',
        ]);
    }

    public function asset(string $asset): BinaryFileResponse
    {
        abort_unless(array_key_exists($asset, self::ASSETS), 404);
        $path = $this->frontendPath($asset);
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => self::ASSETS[$asset],
            'Cache-Control' => 'no-store',
        ]);
    }

    private function frontendPath(string $path): string
    {
        return base_path("../frontend/{$path}");
    }
}
