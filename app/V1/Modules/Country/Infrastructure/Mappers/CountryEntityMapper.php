<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Infrastructure\Mappers;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;

class CountryEntityMapper
{
    /**
     * @param array<string, mixed> $country
     */
    public function parseToEntity(array $country): CountryEntity
    {
        return CountryEntity::fromArray($country);
    }
}
