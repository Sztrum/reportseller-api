<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Aggregates;

use App\V1\Modules\Platform\Domain\Exceptions\PlatformBySlugNotFoundException;
use App\V1\Modules\Platform\Infrastructure\Repositories\PlatformRepository;

readonly class ReportAggregate
{
    public function __construct(
        private PlatformRepository $repository
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function checkIfPlatformExistsBySlug(
        string $slug
    ): self {
        throw_if(
            $this->repository->findBySlug($slug),
            PlatformBySlugNotFoundException::class,
            __('platform::domain.platform_by_slug_not_found')
        );

        return $this;
    }
}
