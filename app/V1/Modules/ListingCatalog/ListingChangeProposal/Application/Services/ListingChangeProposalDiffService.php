<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Application\Services;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;

class ListingChangeProposalDiffService
{
    /**
     * @param array<string, mixed> $proposedPayload
     */
    public function hasListingPayloadChanges(
        Listing $listing,
        array $proposedPayload
    ): bool {
        $currentPayload = [
            'platform_id' => $listing->platform_id,
            'report_category_id' => $listing->report_category_id,
            'external_id' => $listing->external_id,
            'url' => $listing->url,
            'title' => $listing->title,
            'description' => $listing->description,
            'price' => $listing->price,
            'currency' => $listing->currency,
            'seller_type' => $listing->seller_type?->value,
            'seller_name' => $listing->seller_name,
            'location' => $listing->location,
            'thumbnail_url' => $listing->thumbnail_url,
            'is_active_external' => $listing->is_active_external,
        ];
        ksort($currentPayload);
        ksort($proposedPayload);

        return $currentPayload !== $proposedPayload;
    }
}
