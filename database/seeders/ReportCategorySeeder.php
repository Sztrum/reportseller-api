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
            ['name' => 'Ogólne', 'slug' => 'ogolne', 'order' => 1],
            ['name' => 'Motoryzacja', 'slug' => 'motoryzacja', 'order' => 2],
            ['name' => 'Nieruchomości', 'slug' => 'nieruchomosci', 'order' => 3],
            ['name' => 'Praca', 'slug' => 'praca', 'order' => 4],
            ['name' => 'Usługi', 'slug' => 'uslugi', 'order' => 5],
            ['name' => 'Elektronika', 'slug' => 'elektronika', 'order' => 6],
            ['name' => 'Dom i ogród', 'slug' => 'dom-i-ogrod', 'order' => 7],
            ['name' => 'Moda', 'slug' => 'moda', 'order' => 8],
            ['name' => 'Dziecko', 'slug' => 'dziecko', 'order' => 9],
            ['name' => 'Sport i hobby', 'slug' => 'sport-i-hobby', 'order' => 10],
            ['name' => 'Zwierzęta', 'slug' => 'zwierzeta', 'order' => 11],
            ['name' => 'Rolnictwo', 'slug' => 'rolnictwo', 'order' => 12],
            ['name' => 'Firma i przemysł', 'slug' => 'firma-i-przemysl', 'order' => 13],
            ['name' => 'Budownictwo', 'slug' => 'budownictwo', 'order' => 14],
            ['name' => 'Zdrowie i uroda', 'slug' => 'zdrowie-i-uroda', 'order' => 15],
            ['name' => 'Turystyka i noclegi', 'slug' => 'turystyka-i-noclegi', 'order' => 16],
            ['name' => 'Kolekcje i sztuka', 'slug' => 'kolekcje-i-sztuka', 'order' => 17],
            ['name' => 'Inne', 'slug' => 'inne', 'order' => 18],
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
    }
}
