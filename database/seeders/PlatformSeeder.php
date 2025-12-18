<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Database\Seeder;

final class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['name' => 'OLX', 'slug' => 'olx', 'base_url' => 'https://www.olx.pl', 'order' => 1],
            ['name' => 'Otomoto', 'slug' => 'otomoto', 'base_url' => 'https://www.otomoto.pl', 'order' => 2],
            ['name' => 'Allegro', 'slug' => 'allegro', 'base_url' => 'https://allegro.pl', 'order' => 3],
            ['name' => 'Vinted', 'slug' => 'vinted', 'base_url' => 'https://www.vinted.pl', 'order' => 4],
        ];

        foreach ($platforms as $p) {
            Platform::query()->updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'base_url' => $p['base_url'],
                    'order' => $p['order'],
                    'is_active' => true,
                ],
            );
        }

        if (!app()->environment('production')) {
            Platform::factory(5)->create();
        }
    }
}
