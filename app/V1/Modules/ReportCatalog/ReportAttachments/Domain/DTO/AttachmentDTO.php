<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachments\Domain\DTO;

use Spatie\LaravelData\Data;

final class AttachmentDTO extends Data
{
    public function __construct(
        public string $storage_key,
        public ?string $storage_disk = null,
        public ?string $original_name = null,
        public ?string $mime_type = null,
        public ?int $size_bytes = null,
    ) {}
}
