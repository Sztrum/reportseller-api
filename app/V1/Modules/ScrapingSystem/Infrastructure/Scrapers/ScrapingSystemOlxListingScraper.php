<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers;

use App\V1\Modules\ScrapingSystem\Domain\DTO\ScrapingSystemScrapedListingDTO;
use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Support\Str;

class ScrapingSystemOlxListingScraper extends AbstractScrapingSystemListingScraper
{
    protected const SUPPORTED_PLATFORM_SLUGS = ['olx'];

    public function scrape(string $html, string $url, Platform $platform): ScrapingSystemScrapedListingDTO
    {
        $crawler = $this->createCrawler($html);
        $metadata = $this->extractMetadata($html);
        $canonicalUrl = $this->resolveCanonicalUrl($metadata, $url);
        $reportCategoryName = $this->extractTextBySelectors($crawler, [
            'xpath://ol[@data-testid="breadcrumbs"]//li[2]//a',
        ]);
        $sellerTypeLabel = mb_strtolower((string) $this->extractTextBySelectors($crawler, [
            'xpath://p[span[normalize-space()="Prywatne" or normalize-space()="Firmowe"]]',
            'xpath://span[normalize-space()="Prywatne" or normalize-space()="Firmowe"]',
        ]));
        $sellerType = match ($sellerTypeLabel) {
            'prywatne' => 'private',
            'firmowe' => 'business',
            default => null,
        };
        $isActiveExternal = match ($metadata['structured']['availability'] ?? null) {
            'https://schema.org/InStock' => true,
            'https://schema.org/OutOfStock',
            'https://schema.org/SoldOut',
            'https://schema.org/Discontinued' => false,
            default => null,
        };

        return $this->buildScrapedListingDTO(
            platform: $platform,
            canonicalUrl: $canonicalUrl,
            externalId: $this->resolveExternalId($metadata, $canonicalUrl),
            title: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['h1']),
                $metadata['meta']['og:title'] ?? null,
                $metadata['structured']['title'] ?? null,
                $metadata['title'] ?? null,
            ),
            description: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, [
                    'xpath://div[@data-cy="ad_description"]//div[normalize-space()]',
                    'xpath://div[@data-testid="ad_description"]//div[normalize-space()]',
                    'div[data-cy="ad_description"]',
                    '[data-testid="ad_description"]',
                ]),
                $metadata['meta']['og:description'] ?? null,
                $metadata['structured']['description'] ?? null,
            ),
            price: $this->resolvePriceFromCandidates(
                $this->extractTextBySelectors($crawler, [
                    'xpath://div[@data-testid="ad-price-container"]//*[contains(normalize-space(), "zł") or contains(normalize-space(), "PLN")]',
                    '[data-testid="ad-price-container"]',
                ]),
                $metadata['meta']['product:price:amount'] ?? null,
                $metadata['structured']['price'] ?? null,
            ),
            currency: $this->resolveCurrencyFromCandidates(
                $metadata['meta']['product:price:currency'] ?? null,
                $metadata['structured']['currency'] ?? null,
            ),
            sellerName: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['[data-testid="user-profile-user-name"]', '[data-testid="seller_link"]']),
                $metadata['structured']['seller_name'] ?? null,
            ),
            location: $this->firstNonEmptyString(
                $metadata['structured']['location'] ?? null,
                $this->extractTextBySelectors($crawler, ['[data-testid="location-date"]', '[data-testid="ad-location"]']),
            ),
            thumbnailUrl: $this->firstNonEmptyString(
                $metadata['meta']['og:image'] ?? null,
                $metadata['structured']['image'] ?? null,
                $this->extractAttributeBySelectors($crawler, [
                    ['selector' => 'img', 'attr' => 'src'],
                ]),
            ),
            reportCategorySlug: $reportCategoryName !== null ? Str::slug($reportCategoryName) : null,
            sellerType: $sellerType,
            isActiveExternal: $isActiveExternal,
        );
    }
}
