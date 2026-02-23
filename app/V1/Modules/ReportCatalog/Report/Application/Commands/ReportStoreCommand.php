<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;
use App\V1\Modules\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ReportCatalog\Report\Domain\DTO\ReportDTO;
use App\V1\Modules\ReportCatalog\ReportAttachments\Domain\DTO\AttachmentsDTO;
use App\V1\Modules\ReportCatalog\ReportAuthors\Domain\DTO\AuthorDTO;

final readonly class ReportStoreCommand implements CommandTransactionalInterface
{
    public function __construct(
        public ListingDTO $listingDTO,
        public ReportDTO $reportDTO,
        public AuthorDTO $authorDTO,
        public AttachmentsDTO $attachmentsDTO
    ) {}
}
