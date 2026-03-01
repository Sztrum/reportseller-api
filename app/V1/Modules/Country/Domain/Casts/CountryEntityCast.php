<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Casts;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Services\CountryService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Throwable;

/**
 * @implements CastsAttributes<CountryEntity|null, string|null>
 */
readonly class CountryEntityCast implements CastsAttributes
{
    /**
     * @param  mixed     $model
     * @param  mixed     $value
     * @throws Throwable
     */
    public function get($model, string $key, $value, array $attributes): ?CountryEntity
    {
        /** @var CountryService $countryService */
        $countryService = resolve(CountryService::class);

        if (!is_string($value) || $value === '') {
            return null;
        }

        return $countryService->getCountryByCode($value);
    }

    /**
     * @param mixed $model
     * @param mixed $value
     * @param array<string, mixed> $attributes
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof CountryEntity ? $value->getCode() : $value;
    }
}
