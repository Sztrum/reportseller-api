<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\ListingHistory\Domain\Models;

use App\V1\Core\Domain\Models\Model;

class ListingHistory extends Model
{
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
        'is_active_external' => 'bool',
        'captured_at' => 'datetime',
    ];
}
