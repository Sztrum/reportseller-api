<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\DTO;

use Spatie\LaravelData\Data;

final class ReportDTO extends Data
{
    public function __construct(
        public string $title,
        public string $description,
    ) {}
}
