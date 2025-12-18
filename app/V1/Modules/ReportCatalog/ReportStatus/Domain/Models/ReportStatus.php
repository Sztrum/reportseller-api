<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportStatusFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportStatus extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory(): ReportStatusFactory
    {
        return ReportStatusFactory::new();
    }
}
