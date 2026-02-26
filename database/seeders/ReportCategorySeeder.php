<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Illuminate\Database\Seeder;

final class ReportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Motoryzacja', 'slug' => 'motoryzacja', 'order' => 1],
            ['name' => 'Nieruchomości', 'slug' => 'nieruchomosci', 'order' => 2],
            ['name' => 'Elektronika', 'slug' => 'elektronika', 'order' => 3],
            ['name' => 'Dom i ogród', 'slug' => 'dom-i-ogrod', 'order' => 4],
            ['name' => 'Moda', 'slug' => 'moda', 'order' => 5],
            ['name' => 'Dziecko', 'slug' => 'dziecko', 'order' => 6],
            ['name' => 'Sport i hobby', 'slug' => 'sport-i-hobby', 'order' => 7],
            ['name' => 'Zwierzęta', 'slug' => 'zwierzeta', 'order' => 8],
            ['name' => 'Praca', 'slug' => 'praca', 'order' => 9],
            ['name' => 'Usługi', 'slug' => 'uslugi', 'order' => 10],
            ['name' => 'Inne', 'slug' => 'inne', 'order' => 11],
        ];

        foreach ($categories as $c) {
            ReportCategory::query()->updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'description' => null,
                    'order' => $c['order'],
                    'is_active' => true,
                ],
            );
        }

        if (!app()->environment('production')) {
            ReportCategory::factory(2)->create();
        }
    }
}
