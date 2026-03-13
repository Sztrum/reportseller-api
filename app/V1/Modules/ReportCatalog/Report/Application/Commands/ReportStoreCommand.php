<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Application\Commands;

use App\V1\Core\Application\Command\CommandTransactionalInterface;
use App\V1\Modules\ListingCatalog\Listing\Domain\DTO\ListingDTO;
use App\V1\Modules\ReportCatalog\Report\Domain\DTO\ReportDTO;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\DTO\AuthorDTO;

final class ReportStoreCommand implements CommandTransactionalInterface
{
    public ?string $reportId = null;
    public ?string $receiptToken = null;

    public function __construct(
        public ListingDTO $listingDTO,
        public ReportDTO $reportDTO,
        public AuthorDTO $authorDTO,
    ) {}
}
