<?php

declare(strict_types=1);

namespace App\V1\Modules\Reports\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'listing_id',
        'status_id',
        'reporter_email',
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
