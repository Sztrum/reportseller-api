<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachment\Domain\Models;

use App\V1\Core\Domain\Models\Model;

class ReportAttachment extends Model
{
    protected $fillable = [
        'report_id',
        'storage_disk',
        'storage_key',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'int',
    ];

    protected $hidden = [
        'storage_disk',
        'storage_key',
        'created_at',
        'updated_at',
    ];
}
