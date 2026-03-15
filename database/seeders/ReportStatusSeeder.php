<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Seeder;

final class ReportStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Nowe', 'slug' => 'new', 'description' => 'Zgłoszenie dodane i czeka na weryfikację.', 'order' => 1],
            ['name' => 'W trakcie weryfikacji', 'slug' => 'in-progress', 'description' => 'Zgłoszenie jest analizowane.', 'order' => 2],
            ['name' => 'Potwierdzone', 'slug' => 'confirmed', 'description' => 'Zgłoszenie potwierdzone jako zasadne.', 'order' => 3],
            ['name' => 'Odrzucone', 'slug' => 'rejected', 'description' => 'Zgłoszenie odrzucone.', 'order' => 4],
            ['name' => 'Duplikat', 'slug' => 'duplicate', 'description' => 'Zgłoszenie zdublowane z innym.', 'order' => 5],
        ];

        foreach ($statuses as $s) {
            ReportStatus::query()->updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'name' => $s['name'],
                    'is_active' => true,
                ],
            );
        }
    }
}
