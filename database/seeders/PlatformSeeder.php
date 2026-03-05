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
            ['name' => 'Allegro', 'slug' => 'allegro', 'base_url' => 'https://allegro.pl', 'order' => 2],
            ['name' => 'Allegro Lokalnie', 'slug' => 'allegro-lokalnie', 'base_url' => 'https://allegrolokalnie.pl', 'order' => 3],
            ['name' => 'Sprzedajemy.pl', 'slug' => 'sprzedajemy', 'base_url' => 'https://sprzedajemy.pl', 'order' => 4],
            ['name' => 'Lento', 'slug' => 'lento', 'base_url' => 'https://www.lento.pl', 'order' => 5],
            ['name' => 'Gratka', 'slug' => 'gratka', 'base_url' => 'https://gratka.pl', 'order' => 6],
            ['name' => 'Tablica.com', 'slug' => 'tablica', 'base_url' => 'https://tablica.com', 'order' => 7],
            ['name' => 'Dealuj.pl', 'slug' => 'dealuj', 'base_url' => 'https://dealuj.pl', 'order' => 8],
            ['name' => 'LokalneOgłoszenia.com', 'slug' => 'lokalneogloszenia', 'base_url' => 'https://lokalneogloszenia.com', 'order' => 9],
            ['name' => 'Trojmiasto.pl Ogłoszenia', 'slug' => 'trojmiasto-ogloszenia', 'base_url' => 'https://ogloszenia.trojmiasto.pl', 'order' => 10],
            ['name' => 'LM.pl Ogłoszenia', 'slug' => 'lm-ogloszenia', 'base_url' => 'https://www.lm.pl/ogloszenia', 'order' => 11],
            ['name' => 'Oferteo', 'slug' => 'oferteo', 'base_url' => 'https://www.oferteo.pl', 'order' => 12],
            ['name' => 'OTODOM', 'slug' => 'otodom', 'base_url' => 'https://www.otodom.pl', 'order' => 13],
            ['name' => 'Morizon', 'slug' => 'morizon', 'base_url' => 'https://www.morizon.pl', 'order' => 14],
            ['name' => 'Domiporta', 'slug' => 'domiporta', 'base_url' => 'https://www.domiporta.pl', 'order' => 15],
            ['name' => 'Adresowo', 'slug' => 'adresowo', 'base_url' => 'https://adresowo.pl', 'order' => 16],
            ['name' => 'Nieruchomosci-online.pl', 'slug' => 'nieruchomosci-online', 'base_url' => 'https://www.nieruchomosci-online.pl', 'order' => 17],
            ['name' => 'Szybko.pl', 'slug' => 'szybko', 'base_url' => 'https://szybko.pl', 'order' => 18],
            ['name' => 'Domy.pl', 'slug' => 'domy', 'base_url' => 'https://domy.pl', 'order' => 19],
            ['name' => 'RynekPierwotny', 'slug' => 'rynekpierwotny', 'base_url' => 'https://rynekpierwotny.pl', 'order' => 20],
            ['name' => 'GetHome', 'slug' => 'gethome', 'base_url' => 'https://gethome.pl', 'order' => 21],
            ['name' => 'Otomoto', 'slug' => 'otomoto', 'base_url' => 'https://www.otomoto.pl', 'order' => 22],
            ['name' => 'Autoplac', 'slug' => 'autoplac', 'base_url' => 'https://autoplac.pl', 'order' => 23],
            ['name' => 'MotoGratka', 'slug' => 'motogratka', 'base_url' => 'https://motogratka.pl', 'order' => 24],
            ['name' => 'AutoCentrum Ogłoszenia', 'slug' => 'autocentrum-ogloszenia', 'base_url' => 'https://www.autocentrum.pl/ogloszenia', 'order' => 25],
            ['name' => 'Autogielda.pl', 'slug' => 'autogielda', 'base_url' => 'https://www.autogielda.pl', 'order' => 26],
            ['name' => 'Vinted', 'slug' => 'vinted', 'base_url' => 'https://www.vinted.pl', 'order' => 27],
            ['name' => 'Pracuj.pl', 'slug' => 'pracuj', 'base_url' => 'https://www.pracuj.pl/praca', 'order' => 28],
            ['name' => 'OLX Praca', 'slug' => 'olx-praca', 'base_url' => 'https://www.olx.pl/praca', 'order' => 29],
            ['name' => 'RocketJobs', 'slug' => 'rocketjobs', 'base_url' => 'https://rocketjobs.pl', 'order' => 30],
            ['name' => 'No Fluff Jobs', 'slug' => 'no-fluff-jobs', 'base_url' => 'https://nofluffjobs.com/pl', 'order' => 31],
            ['name' => 'Just Join IT', 'slug' => 'justjoinit', 'base_url' => 'https://justjoin.it', 'order' => 32],
            ['name' => 'Bulldogjob', 'slug' => 'bulldogjob', 'base_url' => 'https://bulldogjob.pl', 'order' => 33],
            ['name' => 'AgroGielda', 'slug' => 'agrogielda', 'base_url' => 'https://agrogielda.pl', 'order' => 34],
            ['name' => 'AgroFoto', 'slug' => 'agrofoto', 'base_url' => 'https://www.agrofoto.pl/forum/forum/28-gielda-rolna-rolnicza', 'order' => 35],
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
    }
}
