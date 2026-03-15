<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ReportCatalog\ReportCategory\Domain\Models\ReportCategory;
use Database\Seeders\ReportCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportCategoryIndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_active_report_categories_sorted_by_order(): void
    {
        $this->seed([
            ReportCategorySeeder::class,
        ]);

        ReportCategory::factory()->create([
            'name' => 'Nieaktywna kategoria',
            'slug' => 'inactive-category',
            'description' => 'Ta kategoria nie powinna byc widoczna.',
            'order' => 0,
            'is_active' => false,
        ]);

        ReportCategory::factory()->create([
            'name' => 'Kategoria testowa',
            'slug' => 'test-category',
            'description' => 'Opis kategorii testowej.',
            'order' => 19,
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.report-categories.index'));

        $response
            ->assertOk()
            ->assertJson([
                'status' => 200,
            ])
            ->assertJsonMissing([
                'slug' => 'inactive-category',
            ]);

        $reportCategories = $response->json('data');
        $lastReportCategory = $reportCategories[array_key_last($reportCategories)] ?? [];

        $this->assertIsArray($reportCategories);
        $this->assertSame('ogolne', $reportCategories[0]['slug'] ?? null);
        $this->assertSame('motoryzacja', $reportCategories[1]['slug'] ?? null);
        $this->assertSame('test-category', $lastReportCategory['slug'] ?? null);
        $this->assertSame('Opis kategorii testowej.', $lastReportCategory['description'] ?? null);
        $this->assertArrayNotHasKey('id', $reportCategories[0] ?? []);
        $this->assertArrayNotHasKey('order', $reportCategories[0] ?? []);
        $this->assertArrayNotHasKey('is_active', $reportCategories[0] ?? []);
    }
}
