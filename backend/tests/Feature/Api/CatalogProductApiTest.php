<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PlatformSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PlatformSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }

    public function test_catalog_can_be_filtered_by_ids(): void
    {
        $products = Product::query()->orderBy('id')->take(2)->get();

        $response = $this->getJson('/api/products?ids[]='.$products[0]->id.'&ids[]='.$products[1]->id);

        $response->assertOk()->assertJsonCount(2, 'data');

        $returnedIds = collect($response->json('data'))->pluck('id')->sort()->values()->all();
        $expectedIds = $products->pluck('id')->sort()->values()->all();

        $this->assertSame($expectedIds, $returnedIds);
    }

    public function test_product_detail_includes_image_urls_and_reference_ids(): void
    {
        $product = Product::query()
            ->with(['images', 'categories', 'platforms'])
            ->firstOrFail();

        $response = $this->getJson('/api/products/'.$product->slug);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.categories.0.id', $product->categories->first()?->id)
            ->assertJsonPath('data.platforms.0.id', $product->platforms->first()?->id)
            ->assertJsonPath('data.images.0.id', $product->images->first()?->id);
    }
}
