<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            'PC',
            'Steam',
            'PlayStation',
            'Xbox',
            'Nintendo',
            'Mobile',
            'Epic Games',
            'Battle.net',
        ];

        foreach ($platforms as $name) {
            Platform::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}

