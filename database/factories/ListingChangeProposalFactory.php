<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ListingCatalog\ListingChangeProposal\Domain\Models\ListingChangeProposal;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListingChangeProposal>
 */
final class ListingChangeProposalFactory extends Factory
{
    /** @var class-string<ListingChangeProposal> */
    protected $model = ListingChangeProposal::class;

    public function definition(): array
    {
        $requestedAt = $this->faker->dateTimeBetween('-30 days', '-2 days');
        $reviewedAt = $this->faker->optional(0.65)->dateTimeBetween($requestedAt);
        $status = $this->faker->randomElement([ListingChangeProposal::STATUS_PENDING, ListingChangeProposal::STATUS_APPROVED, ListingChangeProposal::STATUS_REJECTED,]);

        return [
            'listing_id' => Listing::factory(),
            'report_id' => Report::factory(),
            'status' => $status,
            'current_payload' => [],
            'proposed_payload' => [],
            'requested_at' => $requestedAt,
            'reviewed_at' => $reviewedAt,
            'reviewed_by' => $reviewedAt === null ? null : $this->faker->safeEmail(),
            'review_reason' => $reviewedAt === null ? null : $this->faker->sentence(),
        ];
    }
}
