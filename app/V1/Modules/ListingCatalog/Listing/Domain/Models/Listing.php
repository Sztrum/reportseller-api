<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Modules\Platform\Domain\Models\Platform;
use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $platform_id
 * @property string $report_category_id
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
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(ListingFactory::class)]
class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id',
        'report_category_id',
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
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'seller_type' => ListingSellerTypeEnum::class,
        'is_active_external' => 'bool',
        'is_active' => 'bool',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function reportCategory(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }
}
