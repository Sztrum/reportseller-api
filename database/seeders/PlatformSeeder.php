<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        Platform::factory(10)->create();

        Platform::factory()->create([
            'name' => 'Test Platform',
            'slug' => 'test-platform',
            'base_url' => 'https://test.com',
            'is_active' => true,
        ]);
    }
}
