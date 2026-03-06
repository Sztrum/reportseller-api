<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\User\Domain\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($index = 0; $index < 5; $index++) {
            User::withoutEvents(function (): void {
                User::factory()->create([
                    'id' => (string) Str::uuid(),
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'email_verified_at' => now(),
                    'password' => Hash::make('testtest'),
                    'remember_token' => Str::random(10),
                ]);
            });
        }
    }
}
