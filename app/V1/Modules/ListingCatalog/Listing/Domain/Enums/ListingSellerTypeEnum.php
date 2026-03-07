<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Domain\Enums;

enum ListingSellerTypeEnum: string
{
    case PRIVATE = 'private';
    case BUSINESS = 'business';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
