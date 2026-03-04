<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\ReportAttachment\Domain\Models\ReportAttachment;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ReportAttachment>
 */
final class ReportAttachmentFactory extends Factory
{
    /** @var class-string<ReportAttachment> */
    protected $model = ReportAttachment::class;

    public function definition(): array
    {
        $ext = $this->faker->randomElement(['jpg', 'png', 'webp', 'pdf']);
        $extension = is_string($ext) ? $ext : 'jpg';
        $originalName = $this->faker->words(3, true);
        if (!is_string($originalName)) {
            $originalName = implode('_', (array) $originalName);
        }

        return [
            'report_id' => Report::factory(),
            'storage_disk' => $this->faker->randomElement(['s3', 'wasabi']),
            'storage_key' => 'reports/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $extension,
            'original_name' => $originalName . '.' . $extension,
            'mime_type' => $extension === 'pdf' ? 'application/pdf' : 'image/' . $extension,
            'size_bytes' => $this->faker->numberBetween(10_000, 8_000_000),
        ];
    }

    public function image(): self
    {
        $ext = $this->faker->randomElement(['jpg', 'png', 'webp']);
        $extension = is_string($ext) ? $ext : 'jpg';

        return $this->state([
            'mime_type' => 'image/' . $extension,
            'storage_key' => 'reports/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $extension,
            'original_name' => 'attachment.' . $extension,
        ]);
    }

    public function pdf(): self
    {
        return $this->state([
            'mime_type' => 'application/pdf',
            'storage_key' => 'reports/' . now()->format('Y/m') . '/' . Str::uuid() . '.pdf',
            'original_name' => 'attachment.pdf',
        ]);
    }
}
