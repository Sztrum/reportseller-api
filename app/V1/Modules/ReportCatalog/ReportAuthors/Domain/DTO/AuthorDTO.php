<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthors\Domain\DTO;

use Spatie\LaravelData\Data;

final class AuthorDTO extends Data
{
    public function __construct(
        public ?string $consent_version,
        public ?string $consent_given_at,
    ) {
    }
}
