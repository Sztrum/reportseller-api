<?php

declare(strict_types=1);

namespace App\V1\Modules\ScrapingSystem\Application\Services;

use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\Platform\Infrastructure\Repositories\PlatformRepository;
use Illuminate\Support\Collection;

readonly class ScrapingSystemPlatformResolver
{
    public function __construct(
        private PlatformRepository $platformRepository,
    ) {
    }

    public function resolvePlatformForUrl(string $url): ?Platform
    {
        $parsedUrl = parse_url($url);
        $inputHost = $this->normalizeHost($parsedUrl['host'] ?? null);
        $inputPath = trim((string) ($parsedUrl['path'] ?? '/'), '/');

        if ($inputHost === null) {
            return null;
        }

        /** @var Collection<int, Platform> $platforms */
        $platforms = $this->platformRepository->getActivePlatformsOrderedByOrder();

        /** @var Platform|null $platform */
        $platform = $platforms
            ->filter(function (Platform $platform) use ($inputHost, $inputPath): bool {
                $platformUrl = parse_url($platform->base_url);
                $platformHost = $this->normalizeHost($platformUrl['host'] ?? null);
                $platformPath = trim((string) ($platformUrl['path'] ?? ''), '/');

                if ($platformHost !== $inputHost) {
                    return false;
                }

                if ($platformPath === '') {
                    return true;
                }

                return str_starts_with($inputPath, $platformPath);
            })
            ->sortByDesc(static fn (Platform $platform): int => strlen((string) parse_url($platform->base_url, PHP_URL_PATH)))
            ->first();

        if ($platform !== null) {
            return $platform;
        }

        $hostSegments = array_values(array_filter(explode('.', $inputHost)));
        $slugCandidate = $hostSegments[0] ?? null;

        if (!is_string($slugCandidate) || $slugCandidate === '') {
            return null;
        }

        return $this->platformRepository->firstActiveBySlug($slugCandidate);
    }

    private function normalizeHost(mixed $host): ?string
    {
        if (!is_string($host) || trim($host) === '') {
            return null;
        }

        return preg_replace('/^www\./', '', strtolower(trim($host)));
    }
}
