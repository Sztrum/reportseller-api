<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Platform\Domain\Models\Platform;
use Illuminate\Support\Collection;

class PlatformRepository extends EloquentModelRepository
{
    public function model(): Platform
    {
        return new Platform();
    }

    public function moduleName(): string
    {
        return 'platform';
    }

    public function fistBySlug(
        string $slug
    ): ?Platform {
        /** @var Platform|null $platform */
        $platform = $this->query()
            ->where('slug', $slug)
            ->first();

        return $platform;
    }

    public function firstActiveBySlug(
        string $slug
    ): ?Platform {
        /** @var Platform|null $platform */
        $platform = $this->query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        return $platform;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getActivePlatformsOrderedByOrder(): Collection
    {
        /** @var Collection<int, Platform> $platforms */
        $platforms = $this->query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return $platforms;
    }
}
