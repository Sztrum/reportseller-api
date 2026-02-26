<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    protected static function newFactory(): ReportStatusFactory
    {
        return ReportStatusFactory::new();
    }
}
