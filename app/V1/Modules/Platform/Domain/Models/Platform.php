<?php

declare(strict_types=1);

namespace App\V1\Modules\Platform\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\PlatformFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $base_url
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(PlatformFactory::class)]
class Platform extends Model
{
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
}
