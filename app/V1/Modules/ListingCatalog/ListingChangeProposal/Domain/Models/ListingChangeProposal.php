<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Database\Factories\ListingChangeProposalFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $listing_id
 * @property string $report_id
 * @property string $status
 * @property array<string, mixed> $current_payload
 * @property array<string, mixed> $proposed_payload
 * @property \Illuminate\Support\Carbon $requested_at
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property string|null $reviewed_by
 * @property string|null $review_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(ListingChangeProposalFactory::class)]
class ListingChangeProposal extends Model
{
    /** @use HasFactory<ListingChangeProposalFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'listing_id',
        'report_id',
        'status',
        'current_payload',
        'proposed_payload',
        'requested_at',
        'reviewed_at',
        'reviewed_by',
        'review_reason',
    ];

    protected $casts = [
        'current_payload' => 'array',
        'proposed_payload' => 'array',
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
