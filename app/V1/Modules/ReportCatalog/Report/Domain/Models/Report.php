<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $listing_id
 * @property string $report_status_id
 * @property string $report_author_id
 * @property string $title
 * @property string $description
 * @property string|null $moderation_note
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
#[UseFactory(ReportFactory::class)]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'listing_id',
        'report_status_id',
        'report_author_id',
        'title',
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
