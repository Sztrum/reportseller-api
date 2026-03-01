<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ListingHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'is_active_external' => 'bool',
        'captured_at' => 'datetime',
    ];
}
