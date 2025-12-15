<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\Reports\Domain\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        Report::factory(200)->create();
    }
}
