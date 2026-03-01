<?php

declare(strict_types=1);

namespace App\V1\Modules\Country\Domain\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonException;
use JsonSerializable;
use Throwable;

/**
 * @implements Arrayable<string, mixed>
 */
readonly class CountryEntity implements Arrayable, JsonSerializable, Jsonable
{
    /**
     * @param array<int, string> $phone
     * @param array<int, string> $currency
     * @param array<int, string> $languages
     */
    public function __construct(
        private string $code,
        private string $name,
        private string $native,
        private array  $phone,
        private string $continent,
        private string $capital,
        private array  $currency,
        private array  $languages,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNative(): string
    {
        return $this->native;
    }

    /**
     * @return array<int, string>
     */
    public function getPhone(): array
    {
        return $this->phone;
    }

    public function getContinent(): string
    {
        return $this->continent;
    }

    public function getCapital(): string
    {
        return $this->capital;
    }

    /**
     * @return array<int, string>
     */
    public function getCurrency(): array
    {
        return $this->currency;
    }

    /**
     * @return array<int, string>
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function hasLanguage(string $code): bool
    {
        return in_array($code, $this->languages, true);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'native' => $this->getNative(),
            'phone' => $this->getPhone(),
            'continent' => $this->getContinent(),
            'capital' => $this->getCapital(),
            'currency' => $this->getCurrency(),
            'languages' => $this->getLanguages(),
        ];
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @param array<string, mixed> $data
     * @throws Throwable
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: self::stringValue($data, 'code'),
            name: self::stringValue($data, 'name'),
            native: self::stringValue($data, 'native'),
            phone: self::stringListValue($data, 'phone'),
            continent: self::stringValue($data, 'continent'),
            capital: self::stringValue($data, 'capital'),
            currency: self::stringListValue($data, 'currency'),
            languages: self::stringListValue($data, 'languages'),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param mixed $options
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        if (!is_int($options)) {
            $options = 0;
        }

        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws Throwable
     */
    private static function stringValue(array $data, string $key): string
    {
        $value = $data[$key] ?? null;
        throw_if(!is_string($value), new \InvalidArgumentException(sprintf('Invalid "%s" country value.', $key)));

        return $value;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private static function stringListValue(array $data, string $key): array
    {
        $value = $data[$key] ?? null;
        throw_if(!is_array($value), new \InvalidArgumentException(sprintf('Invalid "%s" country value.', $key)));

        return array_values(array_filter($value, static fn (mixed $item): bool => is_string($item)));
    }
}
