<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Infrastructure\Repositories;

use App\V1\Core\Infrastructure\Repositories\Eloquent\EloquentModelRepository;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;

class ReportRepository extends EloquentModelRepository
{
    private const RECEIPT_EXPIRED_STATUS_SLUG = 'confirmed';

    public function model(): Report
    {
        return new Report();
    }

    public function moduleName(): string
    {
        return 'report';
    }

    public function firstByReceiptTokenWithReceiptRelations(string $receiptToken): ?Report
    {
        /** @var Report|null $report */
        $report = $this->query()
            ->with([
                'listing.platform',
                'reportStatus',
            ])
            ->where('receipt_token_hash', hash('sha256', $receiptToken))
            ->where('is_published', false)
            ->whereNull('published_at')
            ->whereHas('reportStatus', static function ($query): void {
                $query->where('slug', '!=', self::RECEIPT_EXPIRED_STATUS_SLUG);
            })
            ->first();

        return $report;
    }
}
