<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachment\Domain\DTO;

use Spatie\LaravelData\Data;

final class AttachmentsDTO extends Data
{
    /** @param array<int, AttachmentDTO> $items */
    public function __construct(
        public array $items = [],
    ) {}
}
