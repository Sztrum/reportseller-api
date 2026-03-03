<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingChangeProposal\Application\Services;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;

class ListingChangeProposalDiffService
{
    /**
     * @param array<string, mixed> $proposedPayload
     */
    public function shouldCreateProposal(
        Listing $listing,
        array $proposedPayload
    ): bool {
        return $listing->platform_id !== $proposedPayload['platform_id']
            || $listing->report_category_id !== $proposedPayload['report_category_id']
            || $listing->external_id !== $proposedPayload['external_id']
            || $listing->url !== $proposedPayload['url']
            || $listing->title !== $proposedPayload['title']
            || $listing->description !== $proposedPayload['description']
            || $listing->price !== $proposedPayload['price']
            || $listing->currency !== $proposedPayload['currency']
            || $listing->seller_type !== $proposedPayload['seller_type']
            || $listing->seller_name !== $proposedPayload['seller_name']
            || $listing->location !== $proposedPayload['location']
            || $listing->thumbnail_url !== $proposedPayload['thumbnail_url']
            || $listing->is_active_external !== $proposedPayload['is_active_external'];
    }

    /**
     * @return array<string, mixed>
     */
    public function currentPayload(
        Listing $listing
    ): array {
        return [
            'platform_id' => $listing->platform_id,
            'report_category_id' => $listing->report_category_id,
            'external_id' => $listing->external_id,
            'url' => $listing->url,
            'title' => $listing->title,
            'description' => $listing->description,
            'price' => $listing->price,
            'currency' => $listing->currency,
            'seller_type' => $listing->seller_type,
            'seller_name' => $listing->seller_name,
            'location' => $listing->location,
            'thumbnail_url' => $listing->thumbnail_url,
            'is_active_external' => $listing->is_active_external,
        ];
    }
}
