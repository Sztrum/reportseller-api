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
        $currentPrice = $this->faker->numberBetween(1_000, 10_000);
        $proposedPrice = $currentPrice + $this->faker->numberBetween(100, 2_000);

        return [
            'listing_id' => Listing::factory(),
            'report_id' => Report::factory(),
            'status' => ListingChangeProposal::STATUS_PENDING,
            'current_payload' => [
                'title' => 'Aktualny tytul',
                'price' => $currentPrice,
                'currency' => 'PLN',
                'location' => 'Warszawa',
                'is_active_external' => true,
            ],
            'proposed_payload' => [
                'title' => 'Proponowany tytul',
                'price' => $proposedPrice,
                'currency' => 'PLN',
                'location' => 'Warszawa',
                'is_active_external' => true,
            ],
            'requested_at' => now()->subHours(4),
            'reviewed_at' => null,
            'reviewed_by' => null,
            'review_reason' => null,
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'status' => ListingChangeProposal::STATUS_PENDING,
            'reviewed_at' => null,
            'reviewed_by' => null,
            'review_reason' => null,
        ]);
    }

    public function approved(string $reviewedBy = 'moderator@example.com'): self
    {
        return $this->state([
            'status' => ListingChangeProposal::STATUS_APPROVED,
            'reviewed_at' => now()->subHours(1),
            'reviewed_by' => $reviewedBy,
            'review_reason' => 'Zmiana zaakceptowana po weryfikacji.',
        ]);
    }

    public function rejected(string $reviewedBy = 'moderator@example.com'): self
    {
        return $this->state([
            'status' => ListingChangeProposal::STATUS_REJECTED,
            'reviewed_at' => now()->subHours(1),
            'reviewed_by' => $reviewedBy,
            'review_reason' => 'Zmiana odrzucona przez moderatora.',
        ]);
    }
}
