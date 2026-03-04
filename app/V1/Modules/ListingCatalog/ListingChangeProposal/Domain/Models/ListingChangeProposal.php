<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ListingChangeProposalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected static function newFactory(): ListingChangeProposalFactory
    {
        return ListingChangeProposalFactory::new();
    }
}
