<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportAuthor>
 */
final class ReportAuthorFactory extends Factory
{
    /** @var class-string<ReportAuthor> */
    protected $model = ReportAuthor::class;

    public function definition(): array
    {
        return [
            'ip_hash' => hash('sha256', $this->faker->ipv4()),
            'consent_version' => 'v1',
            'consent_given_at' => $this->faker->dateTimeBetween('-6 months'),
            'is_blocked' => false,
            'blocked_at' => null,
        ];
    }
}
