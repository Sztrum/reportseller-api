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
        $consented = $this->faker->boolean(70);

        return [
            'ip_hash' => $this->faker->optional()->sha256(),
            'consent_version' => $consented ? $this->faker->randomElement(['v1', 'v1.1']) : null,
            'consent_given_at' => $consented ? now()->subDays($this->faker->numberBetween(0, 30)) : null,
            'is_blocked' => false,
            'blocked_at' => null,
        ];
    }

    public function blocked(): self
    {
        return $this->state([
            'is_blocked' => true,
            'blocked_at' => now(),
        ]);
    }

    public function consented(string $version = 'v1'): self
    {
        return $this->state([
            'consent_version' => $version,
            'consent_given_at' => now()->subHours(2),
        ]);
    }

    public function anonymous(): self
    {
        return $this->state([
            'ip_hash' => null,
            'consent_version' => null,
            'consent_given_at' => null,
        ]);
    }
}
