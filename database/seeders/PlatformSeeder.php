<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        Platform::factory()->create([
            'name' => 'OLX',
            'slug' => 'olx',
            'base_url' => 'https://www.olx.pl',
            'is_active' => true,
        ]);

        Platform::factory(10)->create();
    }
}
