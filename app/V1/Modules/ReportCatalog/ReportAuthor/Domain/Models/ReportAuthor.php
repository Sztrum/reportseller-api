<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models;

use App\V1\Core\Domain\Models\Model;
use Database\Factories\ReportAuthorFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string|null $email
 * @property string|null $consent_version
 * @property \Illuminate\Support\Carbon|null $consent_given_at
 * @property bool $is_blocked
 * @property \Illuminate\Support\Carbon|null $blocked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
#[UseFactory(ReportAuthorFactory::class)]
class ReportAuthor extends Model
{
    /** @use HasFactory<ReportAuthorFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
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
        'email',
        'consent_version',
        'consent_given_at',
        'blocked_at',
        'created_at',
        'updated_at',
    ];
}
