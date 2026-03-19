<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers;

use App\V1\Modules\ScrapingSystem\Domain\Contracts\ScrapingSystemListingScraperInterface;
use App\V1\Modules\ScrapingSystem\Domain\DTO\ScrapingSystemScrapedListingDTO;
use App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\Support\ScrapingSystemMetadataExtractor;
use App\V1\Modules\Platform\Domain\Models\Platform;
use DOMDocument;
use DOMNode;
use DOMXPath;

abstract class AbstractScrapingSystemListingScraper implements ScrapingSystemListingScraperInterface
{
    protected const SUPPORTED_PLATFORM_SLUGS = [];

    public function __construct(
        protected readonly ScrapingSystemMetadataExtractor $scrapingSystemMetadataExtractor,
    ) {}

    public function supports(Platform $platform): bool
    {
        return in_array($platform->slug, static::SUPPORTED_PLATFORM_SLUGS, true);
    }

    abstract public function scrape(
        string $html,
        string $url,
        Platform $platform,
    ): ScrapingSystemScrapedListingDTO;

    protected function createCrawler(string $html): DOMXPath
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();

        return new DOMXPath($document);
    }

    protected function extractMetadata(string $html): array
    {
        return $this->scrapingSystemMetadataExtractor->extract($html);
    }

    /**
     * @param array<int, string> $selectors
     */
    protected function extractTextBySelectors(DOMXPath $crawler, array $selectors): ?string
    {
        foreach ($selectors as $selector) {
            $nodes = str_starts_with($selector, 'xpath:')
                ? $crawler->query(substr($selector, 6))
                : $crawler->query($this->selectorToXPath($selector));

            if ($nodes === false || $nodes->length === 0) {
                continue;
            }

            $text = $this->extractNodeText($crawler, $nodes->item(0));
            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }

    /**
     * @param array<int, array{selector: string, attr: string}> $selectors
     */
    protected function extractAttributeBySelectors(DOMXPath $crawler, array $selectors): ?string
    {
        foreach ($selectors as $config) {
            $nodes = $crawler->query($this->selectorToXPath($config['selector']));
            if ($nodes === false || $nodes->length === 0) {
                continue;
            }

            $value = $nodes->item(0)?->attributes?->getNamedItem($config['attr'])?->nodeValue;
            if (is_string($value)) {
                $normalizedValue = $this->normalizeExtractedString($value, collapseWhitespace: false);
                if ($normalizedValue !== null) {
                    return $normalizedValue;
                }
            }
        }

        return null;
    }

    protected function selectorToXPath(string $selector): string
    {
        $selector = trim($selector);

        if ($selector === '') {
            return '//*';
        }

        if (preg_match('/^\.(?<class>[A-Za-z0-9_-]+)$/', $selector, $matches) === 1) {
            return sprintf(
                '//*[contains(concat(" ", normalize-space(@class), " "), " %s ")]',
                $matches['class']
            );
        }

        if (preg_match('/^\[(?<attr>[A-Za-z0-9:_-]+)="(?<value>[^"]+)"\]$/', $selector, $matches) === 1) {
            return sprintf('//*[@%s="%s"]', $matches['attr'], $matches['value']);
        }

        if (preg_match('/^(?<tag>[A-Za-z0-9]+)\[(?<attr>[A-Za-z0-9:_-]+)="(?<value>[^"]+)"\]$/', $selector, $matches) === 1) {
            return sprintf('//%s[@%s="%s"]', $matches['tag'], $matches['attr'], $matches['value']);
        }

        if (preg_match('/^(?<tag>[A-Za-z0-9]+)$/', $selector, $matches) === 1) {
            return sprintf('//%s', $matches['tag']);
        }

        return '//*';
    }

    protected function resolveCanonicalUrl(array $metadata, string $url): string
    {
        return $this->firstNonEmptyString(
            $metadata['canonical_url'] ?? null,
            $metadata['meta']['og:url'] ?? null,
            $url,
        ) ?? $url;
    }

    protected function buildScrapedListingDTO(
        Platform $platform,
        string $canonicalUrl,
        ?string $externalId,
        ?string $title,
        ?string $description,
        ?int $price,
        ?string $currency,
        ?string $sellerName,
        ?string $location,
        ?string $thumbnailUrl,
        ?string $reportCategorySlug = null,
        ?string $sellerType = null,
        ?bool $isActiveExternal = null,
    ): ScrapingSystemScrapedListingDTO {
        return ScrapingSystemScrapedListingDTO::from([
            'url' => $canonicalUrl,
            'platform_slug' => $platform->slug,
            'report_category_slug' => $reportCategorySlug,
            'external_id' => $externalId,
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'currency' => $currency,
            'seller_type' => $sellerType,
            'seller_name' => $sellerName,
            'location' => $location,
            'thumbnail_url' => $thumbnailUrl,
            'is_active_external' => $isActiveExternal,
        ]);
    }

    protected function resolvePriceFromCandidates(?string ...$candidates): ?int
    {
        foreach ($candidates as $candidate) {
            if (!is_string($candidate)) {
                continue;
            }

            $rawPrice = $this->normalizeExtractedString($candidate, collapseWhitespace: false);
            if ($rawPrice === null) {
                continue;
            }

            $normalized = preg_replace('/[^0-9.,]/', '', $rawPrice);
            if (!is_string($normalized) || $normalized === '') {
                continue;
            }

            $normalized = str_replace(',', '.', $normalized);
            $floatPrice = (float) $normalized;

            if ($floatPrice > 0) {
                return (int) round($floatPrice);
            }
        }

        return null;
    }

    protected function resolveCurrencyFromCandidates(?string ...$candidates): ?string
    {
        $currency = $this->firstNonEmptyString(...$candidates);

        return $currency !== null ? strtoupper($currency) : null;
    }

    protected function resolveExternalId(array $metadata, string $url): ?string
    {
        $structuredExternalId = $metadata['structured']['external_id'] ?? null;
        if (is_string($structuredExternalId) && trim($structuredExternalId) !== '') {
            return trim($structuredExternalId);
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        $lastSegment = $segments[array_key_last($segments)] ?? null;

        if (!is_string($lastSegment) || $lastSegment === '') {
            return null;
        }

        return preg_match('/[0-9]/', $lastSegment) === 1 ? $lastSegment : null;
    }

    protected function firstNonEmptyString(?string ...$values): ?string
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                continue;
            }

            $normalizedValue = $this->normalizeExtractedString($value);
            if ($normalizedValue !== null) {
                return $normalizedValue;
            }
        }

        return null;
    }

    private function extractNodeText(DOMXPath $crawler, ?DOMNode $node): string
    {
        if ($node === null) {
            return '';
        }

        $textNodes = $crawler->query('.//text()[not(ancestor::script) and not(ancestor::style) and not(ancestor::noscript)]', $node);
        if ($textNodes === false || $textNodes->length === 0) {
            return '';
        }

        $textParts = [];

        foreach ($textNodes as $textNode) {
            $normalizedValue = $this->normalizeExtractedString($textNode->nodeValue ?? '');
            if ($normalizedValue !== null) {
                $textParts[] = $normalizedValue;
            }
        }

        return trim(implode(' ', $textParts));
    }

    protected function normalizeExtractedString(string $value, bool $collapseWhitespace = true): ?string
    {
        $normalizedValue = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalizedValue = trim($normalizedValue);

        if ($normalizedValue === '') {
            return null;
        }

        if ($collapseWhitespace) {
            $normalizedValue = preg_replace('/\s+/u', ' ', $normalizedValue);
            if (!is_string($normalizedValue)) {
                return null;
            }

            $normalizedValue = preg_replace('/\s+([,.!?;:])/u', '$1', $normalizedValue);
            if (!is_string($normalizedValue)) {
                return null;
            }
        }

        return trim($normalizedValue) !== '' ? trim($normalizedValue) : null;
    }
}
