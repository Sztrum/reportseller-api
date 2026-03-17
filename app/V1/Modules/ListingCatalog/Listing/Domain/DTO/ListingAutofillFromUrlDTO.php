<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Domain\DTO;

use Spatie\LaravelData\Data;

final class ListingAutofillFromUrlDTO extends Data
{
    public function __construct(
        public string $url,
    ) {
    }
}
