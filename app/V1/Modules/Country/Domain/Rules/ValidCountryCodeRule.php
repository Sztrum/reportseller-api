<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Rules;

use App\V1\Modules\Country\Domain\Services\CountryService;
use Illuminate\Contracts\Validation\Rule;
use Throwable;

class ValidCountryCodeRule implements Rule
{
    /**
     * @param mixed $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        /** @var CountryService $countryService */
        $countryService = resolve(CountryService::class);

        if (!is_string($value) || $value === '') {
            return false;
        }

        try {
            $countryService->getCountryByCode($value);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function message(): string
    {
        return __('countries::validation.invalid_country_code');
    }
}
