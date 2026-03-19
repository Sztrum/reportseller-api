<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\PlatformSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ListingAutofillFromUrlEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_autofills_listing_data_for_popular_seeded_platforms(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            '*' => Http::response($this->genericHtml(), 200),
        ]);

        foreach ($this->popularPlatformUrls() as $platformSlug => $url) {
            $this->getJson(route('api.listings.autofill-from-url', [
                'url' => $url,
            ]))
                ->assertOk()
                ->assertJson([
                    'status' => 200,
                    'data' => [
                        'listing' => [
                        'url' => $url,
                            'platform_slug' => $platformSlug,
                            'report_category_slug' => 'ogolne',
                            'title' => 'Uniwersalne ogloszenie testowe',
                            'description' => 'Opis testowy ogloszenia.',
                            'price' => 999,
                            'currency' => 'PLN',
                            'seller_type' => null,
                            'seller_name' => 'Testowy Sprzedawca',
                            'location' => 'Krakow',
                            'thumbnail_url' => 'https://cdn.example.com/test.jpg',
                        ],
                    ],
                ]);
        }
    }

    public function test_it_autofills_listing_data_from_supported_url(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            'https://olx.pl/oferta/test-listing-123' => Http::response($this->olxHtml(), 200),
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://olx.pl/oferta/test-listing-123',
        ]))
            ->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => [
                    'listing' => [
                        'url' => 'https://www.olx.pl/oferta/test-listing-123',
                        'platform_slug' => 'olx',
                        'report_category_slug' => 'motoryzacja',
                        'external_id' => 'test-listing-123',
                        'title' => 'Super rower miejski',
                        'description' => 'Rower w bardzo dobrym stanie.',
                        'price' => 1250,
                        'currency' => 'PLN',
                        'seller_type' => 'private',
                        'seller_name' => 'Jan Kowalski',
                        'location' => 'Warszawa',
                        'thumbnail_url' => 'https://cdn.example.com/rower.jpg',
                        'is_active_external' => true,
                    ],
                ],
            ]);
    }

    public function test_it_sanitizes_html_css_and_entities_in_scraped_listing_data(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            'https://olx.pl/oferta/test-listing-123' => Http::response($this->olxHtmlWithMarkupNoise(), 200),
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://olx.pl/oferta/test-listing-123',
        ]))
            ->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => [
                    'listing' => [
                        'url' => 'https://www.olx.pl/oferta/test-listing-123',
                        'platform_slug' => 'olx',
                        'report_category_slug' => 'motoryzacja',
                        'external_id' => 'test-listing-123',
                        'title' => 'Super rower miejski',
                        'description' => 'Rower w bardzo dobrym stanie.',
                        'price' => 1250,
                        'currency' => 'PLN',
                        'seller_type' => 'private',
                        'seller_name' => 'Jan Kowalski',
                        'location' => 'Warszawa',
                        'thumbnail_url' => 'https://cdn.example.com/rower.jpg',
                        'is_active_external' => true,
                    ],
                ],
            ]);
    }

    public function test_it_returns_domain_error_for_unsupported_platform_url(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://unsupported.example.com/oferta/1',
        ]))
            ->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Nie obslugujemy wskazanego adresu ogloszenia.',
            ]);
    }

    public function test_it_returns_domain_error_when_platform_has_no_dedicated_scraper(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            'https://dealuj.pl/oferta/test-listing-123' => Http::response($this->genericHtml(), 200),
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://dealuj.pl/oferta/test-listing-123',
        ]))
            ->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Nie obslugujemy wskazanego adresu ogloszenia.',
            ]);
    }

    public function test_it_returns_domain_error_when_remote_fetch_fails(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            'https://olx.pl/oferta/unavailable-123' => Http::response('', 500),
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://olx.pl/oferta/unavailable-123',
        ]))
            ->assertStatus(422)
            ->assertJson([
                'status' => 422,
                'message' => 'Nie udalo sie pobrac danych ogloszenia z podanego adresu.',
            ]);
    }

    public function test_it_detects_platform_using_slug_derived_from_host(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Http::fake([
            'https://olx.pl/oferta/test-listing-123' => Http::response($this->olxHtml(), 200),
        ]);

        $this->getJson(route('api.listings.autofill-from-url', [
            'url' => 'https://olx.pl/oferta/test-listing-123',
        ]))
            ->assertOk()
            ->assertJson([
                'status' => 200,
                'data' => [
                    'listing' => [
                        'platform_slug' => 'olx',
                    ],
                ],
            ]);
    }

    private function olxHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Super rower miejski</title>
    <link rel="canonical" href="https://www.olx.pl/oferta/test-listing-123">
    <meta property="og:title" content="Super rower miejski">
    <meta property="og:description" content="Rower w bardzo dobrym stanie.">
    <meta property="og:image" content="https://cdn.example.com/rower.jpg">
    <meta property="product:price:amount" content="1250">
    <meta property="product:price:currency" content="PLN">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "Super rower miejski",
            "description": "Rower w bardzo dobrym stanie.",
            "sku": "test-listing-123",
            "image": [
                "https://cdn.example.com/rower.jpg"
            ],
            "offers": {
                "@type": "Offer",
                "price": "1250",
                "priceCurrency": "PLN",
                "availability": "https://schema.org/InStock",
                "areaServed": {
                    "@type": "City",
                    "name": "Warszawa"
                }
            }
        }
    </script>
</head>
<body>
    <ol data-testid="breadcrumbs">
        <li><a href="/">Strona główna</a></li>
        <li><a href="/motoryzacja/">Motoryzacja</a></li>
    </ol>
    <div data-testid="ad-price-container">1 250 zł</div>
    <div data-cy="ad_description" data-testid="ad_description">Rower w bardzo dobrym stanie.</div>
    <p><span>Prywatne</span></p>
    <h4 data-testid="user-profile-user-name">Jan Kowalski</h4>
</body>
</html>
HTML;
    }

    private function olxHtmlWithMarkupNoise(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>  Super &nbsp; rower miejski  </title>
    <link rel="canonical" href="https://www.olx.pl/oferta/test-listing-123">
    <meta property="og:title" content="<b>Super</b> rower miejski">
    <meta property="og:description" content="<p>Rower&nbsp;w bardzo dobrym stanie.</p>">
    <meta property="og:image" content="https://cdn.example.com/rower.jpg">
    <meta property="product:price:amount" content="1250">
    <meta property="product:price:currency" content="PLN">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "<strong>Super</strong> rower miejski",
            "description": "<div>Rower&nbsp;w bardzo dobrym stanie.</div>",
            "sku": "test-listing-123",
            "image": [
                "https://cdn.example.com/rower.jpg"
            ],
            "offers": {
                "@type": "Offer",
                "price": "1250",
                "priceCurrency": "PLN",
                "availability": "https://schema.org/InStock",
                "areaServed": {
                    "@type": "City",
                    "name": "Warszawa"
                }
            }
        }
    </script>
</head>
<body>
    <ol data-testid="breadcrumbs">
        <li><a href="/">Strona główna</a></li>
        <li><a href="/motoryzacja/">Motoryzacja</a></li>
    </ol>
    <div data-testid="ad-price-container">1 250 zł</div>
    <div data-cy="ad_description" data-testid="ad_description">
        <style>.offer-hidden{display:none;}</style>
        <h3>Opis</h3>
        <div> Rower <strong>w bardzo dobrym stanie</strong>. </div>
    </div>
    <p><span>Prywatne</span></p>
    <h4 data-testid="user-profile-user-name">Jan&nbsp;Kowalski</h4>
</body>
</html>
HTML;
    }

    /**
     * @return array<string, string>
     */
    private function popularPlatformUrls(): array
    {
        return [
            'olx' => 'https://www.olx.pl/oferta/test-listing-123',
            'allegro' => 'https://allegro.pl/oferta/test-listing-123',
            'allegro-lokalnie' => 'https://allegrolokalnie.pl/oferta/test-listing-123',
            'sprzedajemy' => 'https://sprzedajemy.pl/oferta/test-listing-123',
            'gratka' => 'https://gratka.pl/oferta/test-listing-123',
            'lokalneogloszenia' => 'https://lokalneogloszenia.com/oferta/test-listing-123',
            'otodom' => 'https://www.otodom.pl/pl/oferta/test-listing-123',
            'otomoto' => 'https://www.otomoto.pl/osobowe/oferta/test-listing-123',
            'vinted' => 'https://www.vinted.pl/items/test-listing-123',
        ];
    }

    private function genericHtml(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Uniwersalne ogloszenie testowe</title>
    <meta property="og:title" content="Uniwersalne ogloszenie testowe">
    <meta property="og:description" content="Opis testowy ogloszenia.">
    <meta property="og:image" content="https://cdn.example.com/test.jpg">
    <meta property="product:price:amount" content="999">
    <meta property="product:price:currency" content="PLN">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "Uniwersalne ogloszenie testowe",
            "description": "Opis testowy ogloszenia.",
            "image": "https://cdn.example.com/test.jpg",
            "offers": {
                "@type": "Offer",
                "price": "999",
                "priceCurrency": "PLN",
                "seller": {
                    "@type": "Person",
                    "name": "Testowy Sprzedawca"
                },
                "availableAtOrFrom": {
                    "@type": "Place",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "Krakow"
                    }
                }
            }
        }
    </script>
</head>
<body></body>
</html>
HTML;
    }
}
