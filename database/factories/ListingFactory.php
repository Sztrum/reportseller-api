<?php

declare(strict_types=1);

namespace Database\Factories;

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
        $cities = ['Warszawa', 'Kraków', 'Gdańsk', 'Wrocław', 'Poznań', 'Łódź'];
        $title = $this->faker->randomElement($prefixes) . ' ' . $this->faker->numberBetween(2015, 2025);
        $city = $this->faker->randomElement($cities);
        $slug = Str::slug($title . '-' . $city . '-' . $this->faker->numberBetween(1, 99999));

        return [
            'platform_id' => Platform::factory(),
            'report_category_id' => ReportCategory::factory(),
            'external_id' => (string) Str::uuid(),
            'url' => 'https://www.olx.pl/oferta/' . $slug,
            'title' => $title,
            'description' => sprintf(
                'Przykładowe ogłoszenie testowe: %s. Odbiór osobisty w %s lub wysyłka.',
                mb_strtolower($title),
                $city
            ),
            'price' => $this->faker->numberBetween(600, 150000),
            'currency' => 'PLN',
            'seller_type' => $this->faker->randomElement(['private', 'business']),
            'seller_name' => $this->faker->name(),
            'location' => $city,
            'thumbnail_url' => null,
            'is_active_external' => true,
            'is_active' => false,
        ];
    }

    public function active(): self
    {
        return $this->state([
            'is_active_external' => true,
            'is_active' => true,
        ]);
    }

    public function inactive(): self
    {
        return $this->state([
            'is_active' => false,
        ]);
    }

    public function externalInactive(): self
    {
        return $this->state([
            'is_active_external' => false,
            'is_active' => false,
        ]);
    }

    public function forModeration(): self
    {
        return $this->state([
            'is_active_external' => true,
            'is_active' => false,
        ]);
    }
}
