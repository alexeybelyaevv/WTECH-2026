<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            [
                'code' => 'WELCOME10',
                'type' => PromoCode::TYPE_PERCENT,
                'value' => 10,
                'currency' => 'EUR',
                'starts_at' => now()->subDays(30),
                'ends_at' => now()->addDays(60),
                'usage_limit' => 1000,
                'is_active' => true,
            ],
            [
                'code' => 'SPRING5',
                'type' => PromoCode::TYPE_FIXED,
                'value' => 5,
                'currency' => 'EUR',
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(30),
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED20',
                'type' => PromoCode::TYPE_PERCENT,
                'value' => 20,
                'currency' => 'EUR',
                'starts_at' => now()->subDays(90),
                'ends_at' => now()->subDay(),
                'usage_limit' => 100,
                'is_active' => false,
            ],
        ];

        foreach ($codes as $row) {
            PromoCode::query()->updateOrCreate(
                ['code' => $row['code']],
                $row,
            );
        }
    }
}

