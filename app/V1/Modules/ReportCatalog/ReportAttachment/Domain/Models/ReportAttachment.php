<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAttachment\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $report_id
 * @property string $storage_disk
 * @property string $storage_key
 * @property string $original_name
 * @property string $mime_type
 * @property int $size_bytes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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

    /**
     * @return BelongsTo<Report, $this>
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
