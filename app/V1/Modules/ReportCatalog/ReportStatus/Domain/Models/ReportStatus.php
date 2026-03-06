<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportStatusFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(ReportStatusFactory::class)]
class ReportStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];
}
