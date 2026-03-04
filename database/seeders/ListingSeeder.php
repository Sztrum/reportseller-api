<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ListingSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(20260304);

        $platforms = Platform::query()->whereIn('slug', ['olx', 'allegro', 'otomoto', 'vinted'])->get();
        if ($platforms->isEmpty()) {
            $platforms = collect([
                Platform::factory()->olx()->create(),
                Platform::factory()->create(['slug' => 'allegro', 'name' => 'Allegro', 'base_url' => 'https://allegro.pl', 'is_active' => true]),
                Platform::factory()->create(['slug' => 'otomoto', 'name' => 'Otomoto', 'base_url' => 'https://www.otomoto.pl', 'is_active' => true]),
                Platform::factory()->create(['slug' => 'vinted', 'name' => 'Vinted', 'base_url' => 'https://www.vinted.pl', 'is_active' => true]),
            ]);
        }

        $categories = ReportCategory::query()->whereIn('slug', ['motoryzacja', 'nieruchomosci', 'elektronika', 'dom-i-ogrod', 'sport-i-hobby', 'inne'])->get();
        if ($categories->isEmpty()) {
            $categories = collect([
                ReportCategory::factory()->create(['slug' => 'motoryzacja', 'name' => 'Motoryzacja', 'is_active' => true]),
                ReportCategory::factory()->create(['slug' => 'nieruchomosci', 'name' => 'Nieruchomości', 'is_active' => true]),
                ReportCategory::factory()->create(['slug' => 'elektronika', 'name' => 'Elektronika', 'is_active' => true]),
                ReportCategory::factory()->create(['slug' => 'dom-i-ogrod', 'name' => 'Dom i ogród', 'is_active' => true]),
                ReportCategory::factory()->create(['slug' => 'sport-i-hobby', 'name' => 'Sport i hobby', 'is_active' => true]),
                ReportCategory::factory()->create(['slug' => 'inne', 'name' => 'Inne', 'is_active' => true]),
            ]);
        }

        $cities = ['Warszawa', 'Kraków', 'Gdańsk', 'Wrocław', 'Poznań', 'Lublin', 'Katowice', 'Rzeszów', 'Białystok', 'Szczecin'];
        $privateSellers = ['Jan Kowalski', 'Anna Nowak', 'Marek Wiśniewski', 'Katarzyna Kaczmarek', 'Piotr Mazur', 'Agnieszka Wróbel'];
        $businessSellers = ['Moto-Center', 'Dom-Market', 'Elektronika Pro', 'Sport Outlet', 'Autokomis Partner', 'Home Expert'];
        $offers = [
            ['prefix' => 'Samochód używany', 'price_min' => 18000, 'price_max' => 89000, 'category' => 'motoryzacja'],
            ['prefix' => 'Mieszkanie 2 pokoje', 'price_min' => 320000, 'price_max' => 760000, 'category' => 'nieruchomosci'],
            ['prefix' => 'Laptop gamingowy', 'price_min' => 2200, 'price_max' => 9800, 'category' => 'elektronika'],
            ['prefix' => 'Narożnik rozkładany', 'price_min' => 900, 'price_max' => 4300, 'category' => 'dom-i-ogrod'],
            ['prefix' => 'Rower gravel', 'price_min' => 1200, 'price_max' => 6500, 'category' => 'sport-i-hobby'],
            ['prefix' => 'Telefon premium', 'price_min' => 700, 'price_max' => 5400, 'category' => 'elektronika'],
            ['prefix' => 'Komplet narzędzi', 'price_min' => 120, 'price_max' => 1800, 'category' => 'inne'],
        ];

        for ($index = 1; $index <= 36; $index++) {
            $offer = $offers[array_rand($offers)];
            $category = $categories->firstWhere('slug', $offer['category']) ?? $categories->random();
            $platform = $platforms->random();
            $sellerType = mt_rand(0, 100) > 35 ? 'private' : 'business';
            $sellerName = $sellerType === 'private'
                ? $privateSellers[array_rand($privateSellers)]
                : $businessSellers[array_rand($businessSellers)];
            $price = (string) mt_rand($offer['price_min'], $offer['price_max']);
            $city = $cities[array_rand($cities)];
            $titleSuffix = sprintf('%d/%d', mt_rand(2014, 2025), mt_rand(1, 12));
            $title = $offer['prefix'] . ' ' . $titleSuffix;
            $slug = Str::slug($title . '-' . $city . '-' . $index);
            $url = rtrim((string) $platform->base_url, '/') . '/oferta/' . $slug;
            $description = sprintf(
                'Ogłoszenie testowe: %s. Lokalizacja: %s. Cena do negocjacji, kontakt telefoniczny po godzinie 16:00.',
                mb_strtolower($title),
                $city
            );

            $isActiveExternal = mt_rand(0, 100) > 12;
            $isActive = $isActiveExternal && mt_rand(0, 100) > 28;

            Listing::query()->updateOrCreate(
                ['url' => $url],
                [
                    'platform_id' => $platform->id,
                    'report_category_id' => $category->id,
                    'external_id' => 'mod-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'currency' => 'PLN',
                    'seller_type' => $sellerType,
                    'seller_name' => $sellerName,
                    'location' => $city,
                    'thumbnail_url' => 'https://picsum.photos/seed/listing-' . $index . '/640/480',
                    'is_active_external' => $isActiveExternal,
                    'is_active' => $isActive,
                ],
            );
        }
    }
}
