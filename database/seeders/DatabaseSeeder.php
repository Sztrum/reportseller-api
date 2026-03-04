<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlatformSeeder::class,
            ReportCategorySeeder::class,
            ReportStatusSeeder::class,
        ]);

        if (!app()->environment('production')) {
            $this->call([
                ListingSeeder::class,
                ReportSeeder::class,
                ListingModerationSeeder::class,
            ]);
        }
    }
}
