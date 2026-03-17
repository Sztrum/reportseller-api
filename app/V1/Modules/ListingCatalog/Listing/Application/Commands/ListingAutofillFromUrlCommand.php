<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Application\Commands;

use App\V1\Core\Application\Command\CommandInterface;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;

final class ListingAutofillFromUrlCommand implements CommandInterface
{
    public ?ListingDTO $listingDTO = null;

    public function __construct(
        public string $url,
    ) {
    }
}
