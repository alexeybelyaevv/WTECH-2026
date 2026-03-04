<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->get()->keyBy('slug');
        $platforms = Platform::query()->get()->keyBy('slug');

        $catalog = [
            [
                'name' => 'Steam Wallet 20 EUR',
                'type' => Product::TYPE_DIGITAL,
                'price' => 20.00,
                'stock' => 300,
                'categories' => ['gift-card', 'in-game-currency'],
                'platforms' => ['pc', 'steam'],
                'images' => [
                    'products/steam-wallet-20/front.jpg',
                    'products/steam-wallet-20/back.jpg',
                ],
            ],
            [
                'name' => 'Xbox Game Pass Ultimate 3 Months',
                'type' => Product::TYPE_DIGITAL,
                'price' => 34.99,
                'stock' => 120,
                'categories' => ['subscription'],
                'platforms' => ['xbox', 'pc'],
                'images' => [
                    'products/xbox-game-pass-3m/main.jpg',
                    'products/xbox-game-pass-3m/card.jpg',
                ],
            ],
            [
                'name' => 'PlayStation Plus Essential 12 Months',
                'type' => Product::TYPE_DIGITAL,
                'price' => 59.99,
                'stock' => 100,
                'categories' => ['subscription'],
                'platforms' => ['playstation'],
                'images' => [
                    'products/ps-plus-essential-12m/main.jpg',
                    'products/ps-plus-essential-12m/card.jpg',
                ],
            ],
            [
                'name' => 'Fortnite Battle Pass',
                'type' => Product::TYPE_DIGITAL,
                'price' => 9.99,
                'stock' => 400,
                'categories' => ['battle-pass'],
                'platforms' => ['pc', 'playstation', 'xbox', 'nintendo', 'mobile'],
                'images' => [
                    'products/fortnite-bp/main.jpg',
                    'products/fortnite-bp/hero.jpg',
                ],
            ],
            [
                'name' => 'EA FC 26 Standard Edition Key',
                'type' => Product::TYPE_DIGITAL,
                'price' => 49.99,
                'stock' => 75,
                'categories' => ['game-key'],
                'platforms' => ['pc', 'steam'],
                'images' => [
                    'products/ea-fc-26-key/main.jpg',
                    'products/ea-fc-26-key/cover.jpg',
                ],
            ],
            [
                'name' => 'Dota 2 Aegis Collector Replica',
                'type' => Product::TYPE_PHYSICAL,
                'price' => 79.99,
                'stock' => 18,
                'categories' => ['physical-merchandise'],
                'platforms' => ['pc', 'steam'],
                'images' => [
                    'products/dota2-aegis/front.jpg',
                    'products/dota2-aegis/box.jpg',
                ],
            ],
            [
                'name' => 'PUBG G-Coin 3850',
                'type' => Product::TYPE_DIGITAL,
                'price' => 24.99,
                'stock' => 160,
                'categories' => ['in-game-currency', 'gift-card'],
                'platforms' => ['pc', 'xbox', 'playstation'],
                'images' => [
                    'products/pubg-gcoin-3850/main.jpg',
                    'products/pubg-gcoin-3850/card.jpg',
                ],
            ],
            [
                'name' => 'Minecraft Java & Bedrock Key',
                'type' => Product::TYPE_DIGITAL,
                'price' => 29.99,
                'stock' => 220,
                'categories' => ['game-key'],
                'platforms' => ['pc'],
                'images' => [
                    'products/minecraft-java-bedrock/main.jpg',
                    'products/minecraft-java-bedrock/cover.jpg',
                ],
            ],
        ];

        foreach ($catalog as $row) {
            $slug = Str::slug($row['name']);

            $product = Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $row['name'],
                    'description' => $row['name'].' for gaming platform users.',
                    'type' => $row['type'],
                    'price' => $row['price'],
                    'currency' => 'EUR',
                    'stock' => $row['stock'],
                    'is_active' => true,
                    'metadata' => ['seeded' => true],
                ],
            );

            $categoryIds = collect($row['categories'])
                ->map(fn (string $categorySlug) => $categories->get($categorySlug)?->id)
                ->filter()
                ->values()
                ->all();

            $platformIds = collect($row['platforms'])
                ->map(fn (string $platformSlug) => $platforms->get($platformSlug)?->id)
                ->filter()
                ->values()
                ->all();

            $product->categories()->sync($categoryIds);
            $product->platforms()->sync($platformIds);

            ProductImage::query()->where('product_id', $product->id)->delete();
            foreach ($row['images'] as $sort => $path) {
                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt' => $product->name,
                    'sort_order' => $sort,
                ]);
            }
        }
    }
}

