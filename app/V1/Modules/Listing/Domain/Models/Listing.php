<?php

declare(strict_types=1);

namespace App\V1\Modules\Listing\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'platform_id',
        'report_category_id',
        'external_id',
        'url',
        'title',
        'price',
        'currency',
        'location',
        'thumbnail_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
