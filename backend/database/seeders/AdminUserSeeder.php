<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminData = [
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin12345')),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ];

        $admin = User::query()->where('role', User::ROLE_ADMIN)->first();

        if ($admin) {
            $admin->forceFill($adminData)->save();

            return;
        }

        User::query()->create($adminData);
    }
}
