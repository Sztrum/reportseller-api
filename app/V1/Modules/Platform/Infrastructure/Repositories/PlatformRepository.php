<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\Platform\Domain\Models\Platform;

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
}
