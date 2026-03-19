<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers\Support;

use App\V1\Modules\ScrapingSystem\Domain\Exceptions\ScrapingSystemFetchFailedException;
use DOMDocument;
use DOMXPath;

class ScrapingSystemMetadataExtractor
{
    /**
     * @return array{
     *     title: ?string,
     *     canonical_url: ?string,
     *     meta: array<string, string>,
     *     structured: array<string, string|null>
     * }
     */
    public function extract(string $html): array
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $document->loadHTML($html);
        libxml_clear_errors();

        throw_if(!$loaded, ScrapingSystemFetchFailedException::class);

        $xpath = new DOMXPath($document);

        return [
            'title' => $this->extractTitle($xpath),
            'canonical_url' => $this->extractCanonicalUrl($xpath),
            'meta' => $this->extractMetaTags($xpath),
            'structured' => $this->extractStructuredData($xpath),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function extractMetaTags(DOMXPath $xpath): array
    {
        $meta = [];

        foreach ($xpath->query('//meta[@content]') ?? [] as $node) {
            $key = $node->attributes?->getNamedItem('property')?->nodeValue
                ?? $node->attributes?->getNamedItem('name')?->nodeValue;

            $content = $node->attributes?->getNamedItem('content')?->nodeValue;

            if (!is_string($key) || !is_string($content) || trim($content) === '') {
                continue;
            }

            $normalizedContent = $this->normalizeExtractedString($content, collapseWhitespace: false);
            if ($normalizedContent === null) {
                continue;
            }

            $meta[strtolower(trim($key))] = $normalizedContent;
        }

        return $meta;
    }

    /**
     * @return array<string, string|null>
     */
    private function extractStructuredData(DOMXPath $xpath): array
    {
        $result = [
            'title' => null,
            'description' => null,
            'image' => null,
            'price' => null,
            'currency' => null,
            'seller_name' => null,
            'location' => null,
            'external_id' => null,
            'availability' => null,
        ];

        foreach ($xpath->query('//script[@type="application/ld+json"]') ?? [] as $node) {
            $json = trim($node->textContent);

            if ($json === '') {
                continue;
            }

            /** @var mixed $decoded */
            $decoded = json_decode($json, true);

            if (!is_array($decoded)) {
                continue;
            }

            $nodes = $this->flattenStructuredNodes($decoded);

            foreach ($nodes as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $result['title'] ??= $this->extractString($item['name'] ?? null);
                $result['description'] ??= $this->extractString($item['description'] ?? null);
                $result['image'] ??= $this->extractImage($item['image'] ?? null);
                $result['external_id'] ??= $this->extractString(
                    $item['sku'] ?? $item['productID'] ?? $item['mpn'] ?? null
                );

                $offers = $item['offers'] ?? null;
                if (is_array($offers)) {
                    $result['price'] ??= $this->extractString($offers['price'] ?? null);
                    $result['currency'] ??= $this->extractString($offers['priceCurrency'] ?? null);
                    $result['availability'] ??= $this->extractString($offers['availability'] ?? null);
                    $result['seller_name'] ??= $this->extractString(data_get($offers, 'seller.name'));
                    $result['location'] ??= $this->extractString(
                        data_get($offers, 'availableAtOrFrom.address.addressLocality')
                        ?? data_get($offers, 'areaServed.name')
                    );
                }

                $result['seller_name'] ??= $this->extractString(data_get($item, 'seller.name'));
                $result['location'] ??= $this->extractString(data_get($item, 'address.addressLocality'));
            }
        }

        return $result;
    }

    private function extractTitle(DOMXPath $xpath): ?string
    {
        $title = $xpath->evaluate('string(//title)');

        return is_string($title) ? $this->normalizeExtractedString($title) : null;
    }

    private function extractCanonicalUrl(DOMXPath $xpath): ?string
    {
        $canonical = $xpath->evaluate('string(//link[@rel="canonical"]/@href)');

        return is_string($canonical) ? $this->normalizeExtractedString($canonical, collapseWhitespace: false) : null;
    }

    /**
     * @param array<array-key, mixed> $data
     * @return list<array<array-key, mixed>>
     */
    private function flattenStructuredNodes(array $data): array
    {
        $nodes = [];

        if (array_is_list($data)) {
            foreach ($data as $item) {
                if (is_array($item)) {
                    array_push($nodes, ...$this->flattenStructuredNodes($item));
                }
            }

            return $nodes;
        }

        $nodes[] = $data;

        foreach (['@graph', 'mainEntity', 'itemListElement'] as $nestedKey) {
            $nested = $data[$nestedKey] ?? null;

            if (is_array($nested)) {
                array_push($nodes, ...$this->flattenStructuredNodes($nested));
            }
        }

        return $nodes;
    }

    private function extractImage(mixed $image): ?string
    {
        if (is_string($image) && trim($image) !== '') {
            return $this->normalizeExtractedString($image, collapseWhitespace: false);
        }

        if (is_array($image)) {
            foreach ($image as $value) {
                if (is_string($value) && trim($value) !== '') {
                    return $this->normalizeExtractedString($value, collapseWhitespace: false);
                }
            }
        }

        return null;
    }

    private function extractString(mixed $value): ?string
    {
        return is_string($value) ? $this->normalizeExtractedString($value) : null;
    }

    private function normalizeExtractedString(string $value, bool $collapseWhitespace = true): ?string
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
        }

        return trim($normalizedValue) !== '' ? trim($normalizedValue) : null;
    }
}
