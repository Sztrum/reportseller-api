<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportAuthorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportAuthor extends Model
{
    /** @use HasFactory<ReportAuthorFactory> */
    use HasFactory;

    protected $fillable = [
        'ip_hash',
        'consent_version',
        'consent_given_at',
        'is_blocked',
        'blocked_at',
    ];

    protected $casts = [
        'consent_given_at' => 'datetime',
        'is_blocked' => 'bool',
        'blocked_at' => 'datetime',
    ];

    protected $hidden = [
        'ip_hash',
        'consent_version',
        'consent_given_at',
        'blocked_at',
        'created_at',
        'updated_at',
    ];
}
