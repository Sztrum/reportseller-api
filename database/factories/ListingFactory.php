<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Listing>
 */
final class ListingFactory extends Factory
{
    /** @var class-string<Listing> */
    protected $model = Listing::class;

    public function definition(): array
    {
        $prefixes = [
            'Laptop używany',
            'Rower miejski',
            'Telefon komórkowy',
            'Kanapa narożna',
            'Mieszkanie do wynajęcia',
            'Samochód osobowy',
        ];
        $cities = ['Warszawa', 'Kraków', 'Gdańsk', 'Wrocław', 'Poznań', 'Łódź', 'Bydgoszcz', 'Toruń'];
        $title = $this->faker->randomElement($prefixes) . ' ' . $this->faker->numberBetween(2015, 2026);
        $city = $this->faker->randomElement($cities);
        $slug = Str::slug($title . '-' . $city . '-' . $this->faker->unique()->numberBetween(1, 999999));
        $sellerType = $this->faker->randomElement(ListingSellerTypeEnum::cases());
        $isActiveExternal = $this->faker->boolean(88);

        return [
            'platform_id' => Platform::factory(),
            'report_category_id' => ReportCategory::factory(),
            'external_id' => Str::uuid(),
            'url' => 'https://listing-' . $this->faker->unique()->slug(2) . '.test/oferta/' . $slug,
            'title' => $title,
            'description' => sprintf('Przykładowe ogłoszenie testowe: %s. Odbiór osobisty w %s lub wysyłka.', mb_strtolower($title), $city),
            'price' => $this->faker->numberBetween(600, 150000),
            'currency' => 'PLN',
            'seller_type' => $sellerType,
            'seller_name' => $sellerType === ListingSellerTypeEnum::PRIVATE ? $this->faker->name() : $this->faker->company(),
            'location' => $city,
            'thumbnail_url' => 'https://picsum.photos/seed/' . $this->faker->unique()->slug(2) . '/640/480',
            'is_active_external' => $isActiveExternal,
            'is_active' => $isActiveExternal && $this->faker->boolean(72),
        ];
    }
}
