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
            ['name' => 'Nowe', 'slug' => 'new', 'description' => 'Zgłoszenie dodane i czeka na weryfikację.'],
            ['name' => 'W trakcie weryfikacji', 'slug' => 'in-progress', 'description' => 'Zgłoszenie jest analizowane.'],
            ['name' => 'Zweryfikowane', 'slug' => 'confirmed', 'description' => 'Zgłoszenie potwierdzone jako zasadne.'],
            ['name' => 'Odrzucone', 'slug' => 'rejected', 'description' => 'Zgłoszenie odrzucone.'],
            ['name' => 'Duplikat', 'slug' => 'duplicate', 'description' => 'Zgłoszenie zdublowane z innym.'],
        ];

        foreach ($statuses as $s) {
            ReportStatus::query()->updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'name' => $s['name'],
                    'description' => $s['description'],
                    'is_active' => true,
                ],
            );
        }
    }
}
