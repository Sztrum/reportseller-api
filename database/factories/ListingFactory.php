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
        /** @var list<string> $prefixes */
        $prefixes = __('listing::factory.title_prefixes');
        /** @var list<string> $cities */
        $cities = __('listing::factory.cities');
        /** @var string $titlePrefix */
        $titlePrefix = $this->faker->randomElement($prefixes);
        $title = $titlePrefix . ' ' . $this->faker->numberBetween(2015, 2026);
        /** @var string $city */
        $city = $this->faker->randomElement($cities);
        $slug = Str::slug($title . '-' . $city . '-' . $this->faker->unique()->numberBetween(1, 999999));
        /** @var ListingSellerTypeEnum $sellerType */
        $sellerType = $this->faker->randomElement(ListingSellerTypeEnum::cases());
        $isActiveExternal = $this->faker->boolean(88);

        return [
            'platform_id' => Platform::factory(),
            'report_category_id' => ReportCategory::factory(),
            'external_id' => Str::uuid(),
            'url' => 'https://listing-' . $this->faker->unique()->slug(2) . '.test/oferta/' . $slug,
            'title' => $title,
            'description' => __('listing::factory.description', [
                'title' => mb_strtolower($title),
                'city' => $city,
            ]),
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
