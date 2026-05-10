<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private const PRODUCT_COUNT = 500;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->get()->keyBy('slug');
        $platforms = Platform::query()->get()->keyBy('slug');

        foreach ($this->catalog() as $index => $row) {
            $slug = Str::slug($row['name']);
            $imagePaths = $this->imagePaths($slug);

            $this->writeProductImages(
                $imagePaths,
                $row,
                $index,
            );

            $product = Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'type' => $row['type'],
                    'price' => $row['price'],
                    'currency' => 'EUR',
                    'stock' => $row['stock'],
                    'is_active' => true,
                    'metadata' => [
                        'seeded' => true,
                        'generated' => $row['generated'],
                        'sku' => 'WTECH-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    ],
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
            foreach ($imagePaths as $sort => $path) {
                ProductImage::query()->create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'alt' => $product->name,
                    'sort_order' => $sort,
                ]);
            }
        }
    }

    /**
     * @return list<array{
     *     name: string,
     *     description: string,
     *     type: string,
     *     price: float,
     *     stock: int,
     *     categories: list<string>,
     *     platforms: list<string>,
     *     generated: bool,
     *     amount?: int,
     *     visual?: string
     * }>
     */
    private function catalog(): array
    {
        $catalog = $this->featuredProducts();
        $families = $this->generatedFamilies();
        $brands = ['Nova', 'Apex', 'Pixel', 'Arc', 'Prime', 'Hyper', 'Echo', 'Quantum', 'Rift', 'Nexus'];
        $editions = ['Starter', 'Core', 'Plus', 'Pro', 'Ultimate', 'Deluxe', 'Legend', 'Elite'];

        for ($i = 0; count($catalog) < self::PRODUCT_COUNT; $i++) {
            $family = $families[$i % count($families)];
            $brand = $brands[$i % count($brands)];
            $edition = $editions[(int) floor($i / count($brands)) % count($editions)];
            $amount = $family['amounts'][$i % count($family['amounts'])];
            $batch = (int) floor($i / (count($families) * count($brands))) + 1;
            $name = "{$brand} {$family['name']} {$amount} {$edition}";

            if ($batch > 1) {
                $name .= " Series {$batch}";
            }

            $catalog[] = [
                'name' => $name,
                'description' => "{$name} for fast checkout in the WTECH digital goods store.",
                'type' => $family['type'],
                'price' => round($family['base_price'] + (($i * 7) % 37) + ($amount * $family['amount_multiplier']), 2),
                'stock' => $family['type'] === Product::TYPE_DIGITAL ? 80 + (($i * 13) % 420) : 5 + (($i * 7) % 45),
                'categories' => $family['categories'],
                'platforms' => $this->pickPlatforms($family['platforms'], $i),
                'generated' => true,
                'amount' => $amount,
                'visual' => $family['visual'],
            ];
        }

        return $catalog;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function featuredProducts(): array
    {
        return [
            [
                'name' => 'Steam Wallet 20 EUR',
                'description' => 'Steam Wallet top-up for PC games, DLC, and marketplace purchases.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 20.00,
                'stock' => 300,
                'categories' => ['gift-card', 'in-game-currency'],
                'platforms' => ['pc', 'steam'],
                'generated' => false,
                'amount' => 20,
                'visual' => 'wallet',
            ],
            [
                'name' => 'Xbox Game Pass Ultimate 3 Months',
                'description' => 'Three months of Game Pass Ultimate for Xbox and PC players.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 34.99,
                'stock' => 120,
                'categories' => ['subscription'],
                'platforms' => ['xbox', 'pc'],
                'generated' => false,
                'amount' => 3,
                'visual' => 'subscription',
            ],
            [
                'name' => 'PlayStation Plus Essential 12 Months',
                'description' => 'Annual PlayStation Plus Essential subscription code.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 59.99,
                'stock' => 100,
                'categories' => ['subscription'],
                'platforms' => ['playstation'],
                'generated' => false,
                'amount' => 12,
                'visual' => 'subscription',
            ],
            [
                'name' => 'Fortnite Battle Pass',
                'description' => 'Battle Pass entitlement for the current Fortnite season.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 9.99,
                'stock' => 400,
                'categories' => ['battle-pass'],
                'platforms' => ['pc', 'playstation', 'xbox', 'nintendo', 'mobile'],
                'generated' => false,
                'amount' => 1,
                'visual' => 'battle-pass',
            ],
            [
                'name' => 'EA FC 26 Standard Edition Key',
                'description' => 'Standard edition game key for EA FC 26 on PC.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 49.99,
                'stock' => 75,
                'categories' => ['game-key'],
                'platforms' => ['pc', 'steam'],
                'generated' => false,
                'amount' => 1,
                'visual' => 'game-key',
            ],
            [
                'name' => 'Dota 2 Aegis Collector Replica',
                'description' => 'Physical collector replica inspired by competitive Dota.',
                'type' => Product::TYPE_PHYSICAL,
                'price' => 79.99,
                'stock' => 18,
                'categories' => ['physical-merchandise'],
                'platforms' => ['pc', 'steam'],
                'generated' => false,
                'amount' => 1,
                'visual' => 'physical',
            ],
            [
                'name' => 'PUBG G-Coin 3850',
                'description' => 'PUBG G-Coin bundle for cosmetics and in-game purchases.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 24.99,
                'stock' => 160,
                'categories' => ['in-game-currency', 'gift-card'],
                'platforms' => ['pc', 'xbox', 'playstation'],
                'generated' => false,
                'amount' => 3850,
                'visual' => 'currency',
            ],
            [
                'name' => 'Minecraft Java & Bedrock Key',
                'description' => 'Minecraft Java and Bedrock activation key for PC.',
                'type' => Product::TYPE_DIGITAL,
                'price' => 29.99,
                'stock' => 220,
                'categories' => ['game-key'],
                'platforms' => ['pc'],
                'generated' => false,
                'amount' => 1,
                'visual' => 'game-key',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function generatedFamilies(): array
    {
        return [
            [
                'name' => 'Wallet Card',
                'type' => Product::TYPE_DIGITAL,
                'categories' => ['gift-card'],
                'platforms' => ['pc', 'steam', 'xbox', 'playstation', 'nintendo'],
                'amounts' => [5, 10, 15, 20, 25, 50],
                'base_price' => 4.99,
                'amount_multiplier' => 1.0,
                'visual' => 'wallet',
            ],
            [
                'name' => 'Credit Pack',
                'type' => Product::TYPE_DIGITAL,
                'categories' => ['in-game-currency'],
                'platforms' => ['pc', 'mobile', 'xbox', 'playstation'],
                'amounts' => [500, 1000, 1250, 2500, 5000],
                'base_price' => 3.49,
                'amount_multiplier' => 0.01,
                'visual' => 'currency',
            ],
            [
                'name' => 'Season Pass',
                'type' => Product::TYPE_DIGITAL,
                'categories' => ['battle-pass'],
                'platforms' => ['pc', 'mobile', 'xbox', 'playstation', 'nintendo'],
                'amounts' => [1, 2, 3, 4],
                'base_price' => 8.99,
                'amount_multiplier' => 4.5,
                'visual' => 'battle-pass',
            ],
            [
                'name' => 'Game Key',
                'type' => Product::TYPE_DIGITAL,
                'categories' => ['game-key'],
                'platforms' => ['pc', 'steam', 'epic-games', 'battle-net'],
                'amounts' => [1, 2, 3, 4, 5],
                'base_price' => 14.99,
                'amount_multiplier' => 6.0,
                'visual' => 'game-key',
            ],
            [
                'name' => 'Subscription',
                'type' => Product::TYPE_DIGITAL,
                'categories' => ['subscription'],
                'platforms' => ['pc', 'xbox', 'playstation', 'nintendo', 'mobile'],
                'amounts' => [1, 3, 6, 12],
                'base_price' => 6.99,
                'amount_multiplier' => 5.0,
                'visual' => 'subscription',
            ],
            [
                'name' => 'Collector Box',
                'type' => Product::TYPE_PHYSICAL,
                'categories' => ['physical-merchandise'],
                'platforms' => ['pc', 'steam', 'xbox', 'playstation', 'nintendo'],
                'amounts' => [1, 2, 3],
                'base_price' => 29.99,
                'amount_multiplier' => 18.0,
                'visual' => 'physical',
            ],
        ];
    }

    /**
     * @param  list<string>  $platforms
     * @return list<string>
     */
    private function pickPlatforms(array $platforms, int $index): array
    {
        $first = $platforms[$index % count($platforms)];
        $second = $platforms[($index + 2) % count($platforms)];

        return array_values(array_unique([$first, $second]));
    }

    /**
     * @return list<string>
     */
    private function imagePaths(string $slug): array
    {
        return [
            "products/{$slug}/main.svg",
            "products/{$slug}/gallery.svg",
        ];
    }

    /**
     * @param  list<string>  $paths
     * @param  array<string, mixed>  $row
     */
    private function writeProductImages(array $paths, array $row, int $index): void
    {
        $palette = $this->palette($index);
        $name = (string) $row['name'];
        $category = (string) ($row['categories'][0] ?? 'game-key');
        $platform = (string) ($row['platforms'][0] ?? 'pc');

        Storage::disk('public')->put(
            $paths[0],
            $this->productSvg($row, $name, $category, $platform, $palette, false, $index),
        );

        Storage::disk('public')->put(
            $paths[1],
            $this->productSvg($row, $name, $category, $platform, array_reverse($palette), true, $index),
        );
    }

    /**
     * @return list<string>
     */
    private function palette(int $index): array
    {
        $palettes = [
            ['#111827', '#22c55e', '#a7f3d0'],
            ['#172554', '#38bdf8', '#dbeafe'],
            ['#3b0764', '#f472b6', '#fce7f3'],
            ['#431407', '#fb923c', '#ffedd5'],
            ['#052e16', '#84cc16', '#ecfccb'],
            ['#312e81', '#818cf8', '#e0e7ff'],
            ['#450a0a', '#f87171', '#fee2e2'],
            ['#0f172a', '#facc15', '#fef9c3'],
        ];

        return $palettes[$index % count($palettes)];
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $palette
     */
    private function productSvg(
        array $row,
        string $name,
        string $category,
        string $platform,
        array $palette,
        bool $alternate,
        int $index,
    ): string {
        [$dark, $accent, $light] = $palette;
        $safeName = $this->svgText($name);
        $safeCategory = $this->svgText(Str::of($category)->replace('-', ' ')->title()->toString());
        $safePlatform = $this->svgText(Str::of($platform)->replace('-', ' ')->upper()->toString());
        $shortName = $this->svgText(Str::limit($name, 34, ''));
        $visual = (string) ($row['visual'] ?? $this->visualForCategory($category));
        $amountLabel = $this->amountLabel($row, $category);
        $safeAmountLabel = $this->svgText($amountLabel);
        $visualMarkup = $this->visualMarkup($visual, $safeAmountLabel, $dark, $accent, $light, $alternate);
        $code = str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
        $badgeX = $alternate ? 58 : 418;

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 960 640" role="img" aria-label="{$safeName}">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$dark}"/>
      <stop offset="100%" stop-color="{$accent}"/>
    </linearGradient>
    <linearGradient id="card" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.96"/>
      <stop offset="100%" stop-color="{$light}" stop-opacity="0.92"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="24" stdDeviation="24" flood-color="#000000" flood-opacity="0.28"/>
    </filter>
  </defs>
  <rect width="960" height="640" fill="url(#bg)"/>
  <path d="M0 498 C166 430 308 572 486 498 C628 438 784 420 960 486 L960 640 L0 640 Z" fill="#ffffff" opacity="0.12"/>
{$visualMarkup}
  <g transform="translate({$badgeX} 72)">
    <rect x="0" y="0" width="484" height="74" rx="24" fill="#ffffff" opacity="0.94"/>
    <text x="28" y="46" fill="{$dark}" font-family="Arial, sans-serif" font-size="28" font-weight="800">{$safePlatform}</text>
    <text x="374" y="46" fill="{$dark}" font-family="Arial, sans-serif" font-size="22" font-weight="700" opacity="0.58">#{$code}</text>
  </g>
  <text x="64" y="500" fill="#ffffff" font-family="Arial, sans-serif" font-size="54" font-weight="800">{$shortName}</text>
  <text x="66" y="550" fill="#ffffff" font-family="Arial, sans-serif" font-size="27" font-weight="700" opacity="0.82">{$safeCategory}</text>
</svg>
SVG;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function amountLabel(array $row, string $category): string
    {
        $amount = (int) ($row['amount'] ?? 0);

        return match ((string) ($row['visual'] ?? $this->visualForCategory($category))) {
            'wallet' => $amount > 0 ? "{$amount} EUR" : 'Voucher',
            'currency' => $amount > 0 ? number_format($amount, 0, '.', ' ').' credits' : 'Credit pack',
            'subscription' => $amount > 1 ? "{$amount} months" : '1 month',
            'battle-pass' => 'Season pass',
            'physical' => 'Collector item',
            default => 'Activation key',
        };
    }

    private function visualForCategory(string $category): string
    {
        return match ($category) {
            'gift-card' => 'wallet',
            'in-game-currency' => 'currency',
            'subscription' => 'subscription',
            'battle-pass' => 'battle-pass',
            'physical-merchandise' => 'physical',
            default => 'game-key',
        };
    }

    private function visualMarkup(
        string $visual,
        string $safeAmountLabel,
        string $dark,
        string $accent,
        string $light,
        bool $alternate,
    ): string {
        $rotation = $alternate ? -8 : 8;

        return match ($visual) {
            'wallet' => <<<SVG
  <g transform="translate(214 160) rotate({$rotation} 266 155)" filter="url(#shadow)">
    <rect x="0" y="0" width="532" height="310" rx="34" fill="url(#card)"/>
    <rect x="32" y="34" width="468" height="70" rx="20" fill="{$dark}" opacity="0.92"/>
    <text x="58" y="79" fill="#ffffff" font-family="Arial, sans-serif" font-size="28" font-weight="800">DIGITAL VOUCHER</text>
    <text x="58" y="184" fill="{$dark}" font-family="Arial, sans-serif" font-size="62" font-weight="900">{$safeAmountLabel}</text>
    <rect x="58" y="220" width="164" height="44" rx="14" fill="{$accent}"/>
    <text x="82" y="250" fill="#ffffff" font-family="Arial, sans-serif" font-size="22" font-weight="800">WTECH</text>
    <path d="M344 144 h96 a26 26 0 0 1 26 26 v46 a26 26 0 0 1 -26 26 h-96 a26 26 0 0 1 -26 -26 v-46 a26 26 0 0 1 26 -26z" fill="{$light}" stroke="{$dark}" stroke-width="10" opacity="0.9"/>
    <path d="M338 192 h136" stroke="{$dark}" stroke-width="12" stroke-linecap="round" opacity="0.7"/>
  </g>
SVG,
            'currency' => <<<SVG
  <g transform="translate(250 108)" filter="url(#shadow)">
    <rect x="0" y="0" width="460" height="384" rx="34" fill="url(#card)"/>
    <text x="230" y="78" text-anchor="middle" fill="{$dark}" font-family="Arial, sans-serif" font-size="34" font-weight="900">IN-GAME CURRENCY</text>
    <ellipse cx="180" cy="222" rx="84" ry="34" fill="{$accent}"/>
    <rect x="96" y="154" width="168" height="68" fill="{$accent}"/>
    <ellipse cx="180" cy="154" rx="84" ry="34" fill="{$light}" stroke="{$dark}" stroke-width="8"/>
    <ellipse cx="292" cy="246" rx="84" ry="34" fill="{$accent}"/>
    <rect x="208" y="178" width="168" height="68" fill="{$accent}"/>
    <ellipse cx="292" cy="178" rx="84" ry="34" fill="{$light}" stroke="{$dark}" stroke-width="8"/>
    <text x="230" y="330" text-anchor="middle" fill="{$dark}" font-family="Arial, sans-serif" font-size="44" font-weight="900">{$safeAmountLabel}</text>
  </g>
SVG,
            'subscription' => <<<SVG
  <g transform="translate(250 116)" filter="url(#shadow)">
    <rect x="0" y="0" width="460" height="368" rx="34" fill="url(#card)"/>
    <rect x="0" y="0" width="460" height="96" rx="34" fill="{$dark}"/>
    <rect x="72" y="42" width="42" height="82" rx="18" fill="{$accent}"/>
    <rect x="346" y="42" width="42" height="82" rx="18" fill="{$accent}"/>
    <text x="230" y="62" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="30" font-weight="900">SUBSCRIPTION</text>
    <text x="230" y="214" text-anchor="middle" fill="{$dark}" font-family="Arial, sans-serif" font-size="64" font-weight="900">{$safeAmountLabel}</text>
    <rect x="82" y="260" width="296" height="52" rx="18" fill="{$accent}"/>
    <text x="230" y="295" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="24" font-weight="800">INSTANT CODE</text>
  </g>
SVG,
            'battle-pass' => <<<SVG
  <g transform="translate(206 146) rotate({$rotation} 274 164)" filter="url(#shadow)">
    <path d="M34 0 h480 a34 34 0 0 1 34 34 v94 a56 56 0 0 0 0 112 v54 a34 34 0 0 1 -34 34 h-480 a34 34 0 0 1 -34 -34 v-54 a56 56 0 0 0 0 -112 v-94 a34 34 0 0 1 34 -34z" fill="url(#card)"/>
    <path d="M112 44 h324 v92 h-324z" fill="{$dark}"/>
    <text x="274" y="104" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="38" font-weight="900">BATTLE PASS</text>
    <text x="274" y="218" text-anchor="middle" fill="{$dark}" font-family="Arial, sans-serif" font-size="52" font-weight="900">{$safeAmountLabel}</text>
    <path d="M128 264 h292" stroke="{$accent}" stroke-width="16" stroke-linecap="round" stroke-dasharray="34 22"/>
  </g>
SVG,
            'physical' => <<<SVG
  <g transform="translate(252 110)" filter="url(#shadow)">
    <path d="M94 82 l180 -72 l160 78 l-178 82z" fill="{$light}" stroke="{$dark}" stroke-width="8"/>
    <path d="M94 82 v222 l162 88 v-222z" fill="url(#card)" stroke="{$dark}" stroke-width="8"/>
    <path d="M434 88 v220 l-178 84 v-222z" fill="{$accent}" stroke="{$dark}" stroke-width="8"/>
    <text x="172" y="226" fill="{$dark}" font-family="Arial, sans-serif" font-size="30" font-weight="900">COLLECTOR</text>
    <text x="176" y="268" fill="{$dark}" font-family="Arial, sans-serif" font-size="30" font-weight="900">BOX</text>
    <text x="328" y="246" fill="#ffffff" font-family="Arial, sans-serif" font-size="26" font-weight="900" transform="rotate(-25 328 246)">WTECH</text>
  </g>
SVG,
            default => <<<SVG
  <g transform="translate(270 96) rotate({$rotation} 210 224)" filter="url(#shadow)">
    <rect x="0" y="0" width="420" height="448" rx="34" fill="url(#card)"/>
    <rect x="34" y="36" width="352" height="214" rx="24" fill="{$dark}" opacity="0.94"/>
    <path d="M74 198 L150 118 L204 174 L252 132 L346 198 Z" fill="{$accent}"/>
    <circle cx="318" cy="84" r="30" fill="{$light}"/>
    <path d="M92 320 h118 a34 34 0 1 1 0 42 h-118 a34 34 0 1 1 0 -42z" fill="{$accent}"/>
    <circle cx="92" cy="341" r="12" fill="#ffffff"/>
    <circle cx="210" cy="341" r="12" fill="#ffffff"/>
    <text x="38" y="292" fill="{$dark}" font-family="Arial, sans-serif" font-size="28" font-weight="900">GAME KEY</text>
    <text x="38" y="396" fill="{$dark}" font-family="Arial, sans-serif" font-size="24" font-weight="800">{$safeAmountLabel}</text>
  </g>
SVG,
        };
    }

    private function svgText(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
