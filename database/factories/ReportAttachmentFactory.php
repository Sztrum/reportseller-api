<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ReportCatalog\ReportAttachments\Domain\Models\ReportAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ReportAttachmentFactory extends Factory
{
    protected $model = ReportAttachment::class;

    public function definition(): array
    {
        $ext = $this->faker->randomElement(['jpg', 'png', 'webp', 'pdf']);

        return [
            'report_id' => $this->faker->uuid(),
            'storage_disk' => $this->faker->optional()->randomElement(['s3', 'wasabi']),
            'storage_key' => 'reports/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $ext,
            'original_name' => $this->faker->optional()->words(3, true) . '.' . $ext,
            'mime_type' => $ext === 'pdf' ? 'application/pdf' : 'image/' . $ext,
            'size_bytes' => $this->faker->numberBetween(10_000, 8_000_000),
        ];
    }
}
