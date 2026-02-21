<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'listing_id',
        'report_status_id',
        'report_author_id',
        'description',
        'moderation_note',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'bool',
        'published_at' => 'datetime',
    ];
}
