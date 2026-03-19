<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Infrastructure\Scrapers;

use App\V1\Modules\ScrapingSystem\Domain\DTO\ScrapingSystemScrapedListingDTO;
use App\V1\Modules\Platform\Domain\Models\Platform;

class ScrapingSystemGratkaListingScraper extends AbstractScrapingSystemListingScraper
{
    protected const SUPPORTED_PLATFORM_SLUGS = ['gratka'];

    public function scrape(string $html, string $url, Platform $platform): ScrapingSystemScrapedListingDTO
    {
        $crawler = $this->createCrawler($html);
        $metadata = $this->extractMetadata($html);
        $canonicalUrl = $this->resolveCanonicalUrl($metadata, $url);

        return $this->buildScrapedListingDTO(
            platform: $platform,
            canonicalUrl: $canonicalUrl,
            externalId: $this->resolveExternalId($metadata, $canonicalUrl),
            title: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['h1', '.sticker__title']),
                $metadata['meta']['og:title'] ?? null,
                $metadata['structured']['title'] ?? null,
                $metadata['title'] ?? null,
            ),
            description: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['.descriptionContainer', '[itemprop="description"]']),
                $metadata['meta']['og:description'] ?? null,
                $metadata['structured']['description'] ?? null,
            ),
            price: $this->resolvePriceFromCandidates(
                $this->extractTextBySelectors($crawler, ['.priceInfo__value', '[itemprop="price"]']),
                $metadata['meta']['product:price:amount'] ?? null,
                $metadata['structured']['price'] ?? null,
            ),
            currency: $this->resolveCurrencyFromCandidates(
                $this->extractTextBySelectors($crawler, ['[itemprop="priceCurrency"]']),
                $metadata['meta']['product:price:currency'] ?? null,
                $metadata['structured']['currency'] ?? null,
            ),
            sellerName: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['.seller-box__name']),
                $metadata['structured']['seller_name'] ?? null,
            ),
            location: $this->firstNonEmptyString(
                $this->extractTextBySelectors($crawler, ['.locationInfo', '[itemprop="addressLocality"]']),
                $metadata['structured']['location'] ?? null,
            ),
            thumbnailUrl: $this->firstNonEmptyString(
                $this->extractAttributeBySelectors($crawler, [
                    ['selector' => 'img', 'attr' => 'src'],
                ]),
                $metadata['meta']['og:image'] ?? null,
                $metadata['structured']['image'] ?? null,
            ),
        );
    }

}
