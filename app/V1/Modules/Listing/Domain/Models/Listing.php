<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'is_active_external' => 'bool',
        'is_active' => 'bool',
    ];
}
