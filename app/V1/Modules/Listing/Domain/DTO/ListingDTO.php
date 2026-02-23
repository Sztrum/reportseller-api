<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\DTO;

use Spatie\LaravelData\Data;

final class ListingDTO extends Data
{
    public function __construct(
        public string $url,
        public string $platform_slug,
        public string $report_category_slug,
        public ?string $external_id,
        public ?string $title,
        public ?int $price,
        public ?string $currency,
        public ?string $seller_type,
        public ?string $seller_name,
        public ?string $location,
        public ?string $thumbnail_url,
        public ?bool $is_active_external,
    ) {}
}
