<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Services;

use App\V1\Modules\Country\Domain\Entities\CountryEntity;
use App\V1\Modules\Country\Domain\Entities\CountryEntityCollection;
use App\V1\Modules\Country\Domain\Exceptions\CountryNativeNameNotFoundException;
use App\V1\Modules\Country\Domain\Exceptions\CountryNotFoundException;
use App\V1\Modules\Country\Infrastructure\Mappers\CountryEntityMapper;
use Illuminate\Config\Repository;
use Throwable;

readonly class CountryService
{
    public function __construct(
        private CountryEntityMapper $countryEntityMapper,
        private Repository          $config,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function getCountryByCode(string $code): CountryEntity
    {
        $countryConfig = $this->config->get('countries::countries.countries.' . strtoupper($code));

        throw_if(
            !is_array($countryConfig),
            CountryNotFoundException::class,
            null,
            ['code' => strtoupper($code)]
        );

        return $this->countryEntityMapper->parseToEntity($this->normalizeCountryData($countryConfig, $code));
    }

    /**
     * @throws Throwable
     */
    public function getCountryNativeNameByCode(string $code): string
    {
        $native = $this->getCountryByCode($code)->getNative();

        throw_if(
            !$native,
            CountryNativeNameNotFoundException::class,
            null,
            ['code' => strtoupper($code)]
        );

        return $native;
    }

    /**
     * Return all countries from config by language code
     */
    public function getCountriesByLanguageCode(string $languageCode): CountryEntityCollection
    {
        $countries = new CountryEntityCollection();

        foreach ($this->getCountriesConfig() as $code => $countryData) {
            $country = $this->countryEntityMapper->parseToEntity($this->normalizeCountryData($countryData, mb_strtolower($code, 'UTF-8')));

            if ($country->hasLanguage($languageCode)) {
                $countries->add($country);
            }
        }

        return $countries;
    }

    public function getCurrencyByLangCode(
        string $languageCode,
    ): ?string {
        /** @var CountryEntity|null $countryEntity */
        $countryEntity = $this->getCountriesByLanguageCode($languageCode)->toCollection()->first();

        if ($countryEntity === null) {
            return null;
        }

        $currency = $countryEntity->getCurrency()[0] ?? null;

        return is_string($currency) ? $currency : null;
    }

    public function getAllCountries(): CountryEntityCollection
    {
        $countries = new CountryEntityCollection();

        foreach ($this->getCountriesConfig() as $code => $countryData) {
            $country = $this->countryEntityMapper->parseToEntity($this->normalizeCountryData($countryData, mb_strtolower($code, 'UTF-8')));
            $countries->add($country);
        }

        return $countries;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getCountriesConfig(): array
    {
        $countries = $this->config->get('countries::countries.countries');

        if (!is_array($countries)) {
            return [];
        }

        $result = [];
        foreach ($countries as $code => $countryData) {
            if (!is_string($code) || !is_array($countryData)) {
                continue;
            }

            /** @var array<string, mixed> $normalizedCountryData */
            $normalizedCountryData = [];
            foreach ($countryData as $key => $value) {
                if (!is_string($key)) {
                    continue;
                }

                $normalizedCountryData[$key] = $value;
            }

            $result[$code] = $normalizedCountryData;
        }

        return $result;
    }

    /**
     * @param array<mixed, mixed> $countryData
     * @return array<string, mixed>
     */
    private function normalizeCountryData(array $countryData, string $countryCode): array
    {
        $normalized = ['code' => $countryCode];
        foreach ($countryData as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            $normalized[$key] = $value;
        }

        return $normalized;
    }
}
