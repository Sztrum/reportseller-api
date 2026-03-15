<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use Database\Factories\ListingHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $listing_id
 * @property string $external_id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property int|null $price
 * @property string|null $currency
 * @property ListingSellerTypeEnum|null $seller_type
 * @property string|null $seller_name
 * @property string|null $location
 * @property string|null $thumbnail_url
 * @property bool $is_active_external
 * @property \Illuminate\Support\Carbon $captured_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(ListingHistoryFactory::class)]
class ListingHistory extends Model
{
    /** @use HasFactory<ListingHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'external_id',
        'url',
        'title',
        'description',
        'price',
        'currency',
        'seller_type',
        'seller_name',
        'location',
        'thumbnail_url',
        'is_active_external',
        'captured_at',
    ];

    protected $casts = [
        'price' => 'integer',
        'seller_type' => ListingSellerTypeEnum::class,
        'is_active_external' => 'bool',
        'captured_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Listing, $this>
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}
