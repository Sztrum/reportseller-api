<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\PlatformFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Platform extends Model
{
    /** @use HasFactory<PlatformFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'base_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected static function newFactory(): PlatformFactory
    {
        return PlatformFactory::new();
    }
}
