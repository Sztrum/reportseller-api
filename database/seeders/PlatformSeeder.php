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
            ['name' => 'OLX', 'slug' => 'olx', 'base_url' => 'https://www.olx.pl', 'order' => 1, 'is_active' => true],
            ['name' => 'Allegro', 'slug' => 'allegro', 'base_url' => 'https://allegro.pl', 'order' => 2, 'is_active' => true],
            ['name' => 'Allegro Lokalnie', 'slug' => 'allegro-lokalnie', 'base_url' => 'https://allegrolokalnie.pl', 'order' => 3, 'is_active' => true],
            ['name' => 'Sprzedajemy.pl', 'slug' => 'sprzedajemy', 'base_url' => 'https://sprzedajemy.pl', 'order' => 4, 'is_active' => true],
            ['name' => 'Gratka', 'slug' => 'gratka', 'base_url' => 'https://gratka.pl', 'order' => 5, 'is_active' => true],
            ['name' => 'LokalneOgloszenia.com', 'slug' => 'lokalneogloszenia', 'base_url' => 'https://lokalneogloszenia.com', 'order' => 6, 'is_active' => true],
            ['name' => 'Dealuj.pl', 'slug' => 'dealuj', 'base_url' => 'https://dealuj.pl', 'order' => 7, 'is_active' => false],
            ['name' => 'Trojmiasto.pl Ogloszenia', 'slug' => 'trojmiasto-ogloszenia', 'base_url' => 'https://ogloszenia.trojmiasto.pl', 'order' => 8, 'is_active' => false],
            ['name' => 'Oferteo', 'slug' => 'oferteo', 'base_url' => 'https://www.oferteo.pl', 'order' => 9, 'is_active' => false],
            ['name' => 'OTODOM', 'slug' => 'otodom', 'base_url' => 'https://www.otodom.pl', 'order' => 10, 'is_active' => true],
            ['name' => 'Morizon', 'slug' => 'morizon', 'base_url' => 'https://www.morizon.pl', 'order' => 11, 'is_active' => false],
            ['name' => 'Domiporta', 'slug' => 'domiporta', 'base_url' => 'https://www.domiporta.pl', 'order' => 12, 'is_active' => false],
            ['name' => 'Adresowo', 'slug' => 'adresowo', 'base_url' => 'https://adresowo.pl', 'order' => 13, 'is_active' => false],
            ['name' => 'Nieruchomosci-online.pl', 'slug' => 'nieruchomosci-online', 'base_url' => 'https://www.nieruchomosci-online.pl', 'order' => 14, 'is_active' => false],
            ['name' => 'GetHome', 'slug' => 'gethome', 'base_url' => 'https://gethome.pl', 'order' => 15, 'is_active' => false],
            ['name' => 'Otomoto', 'slug' => 'otomoto', 'base_url' => 'https://www.otomoto.pl', 'order' => 16, 'is_active' => true],
            ['name' => 'Autoplac', 'slug' => 'autoplac', 'base_url' => 'https://autoplac.pl', 'order' => 17, 'is_active' => false],
            ['name' => 'MotoGratka', 'slug' => 'motogratka', 'base_url' => 'https://motogratka.pl', 'order' => 18, 'is_active' => false],
            ['name' => 'Autogielda.pl', 'slug' => 'autogielda', 'base_url' => 'https://www.autogielda.pl', 'order' => 19, 'is_active' => false],
            ['name' => 'Vinted', 'slug' => 'vinted', 'base_url' => 'https://www.vinted.pl', 'order' => 20, 'is_active' => true],
        ];

        Platform::query()
            ->whereNotIn('slug', array_column($platforms, 'slug'))
            ->delete();

        foreach ($platforms as $p) {
            Platform::query()->updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'base_url' => $p['base_url'],
                    'order' => $p['order'],
                    'is_active' => $p['is_active'],
                ],
            );
        }
    }
}
