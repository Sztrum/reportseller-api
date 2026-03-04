<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ReportStatus>
 */
class ReportStatusFactory extends Factory
{
    /** @var class-string<ReportStatus> */
    protected $model = ReportStatus::class;

    public function definition(): array
    {
        $slug = $this->faker->unique()->slug(2);

        return [
            'slug' => $slug,
            'name' => Str::title(str_replace('-', ' ', $slug)),
            'is_active' => $this->faker->boolean(80),
        ];
    }

    public function statusNew(): self
    {
        return $this->status('new', 'Nowe');
    }

    public function inProgress(): self
    {
        return $this->status('in-progress', 'W trakcie');
    }

    public function confirmed(): self
    {
        return $this->status('confirmed', 'Potwierdzone');
    }

    public function rejected(): self
    {
        return $this->status('rejected', 'Odrzucone');
    }

    public function duplicate(): self
    {
        return $this->status('duplicate', 'Duplikat');
    }

    private function status(string $slug, string $name): self
    {
        return $this->state([
            'slug' => $slug,
            'name' => $name,
            'is_active' => true,
        ]);
    }
}
