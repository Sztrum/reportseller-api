<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportCategory extends Model
{
    /** @use HasFactory<ReportCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected static function newFactory(): ReportCategoryFactory
    {
        return ReportCategoryFactory::new();
    }
}
