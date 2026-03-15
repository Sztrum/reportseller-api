<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Database\Seeders\ReportStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportStatusIndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_active_report_statuses_sorted_by_name(): void
    {
        $this->seed([
            ReportStatusSeeder::class,
        ]);

        ReportStatus::factory()->create([
            'name' => 'Nieaktywny status',
            'slug' => 'inactive-status',
            'description' => 'Ten status nie powinien byc widoczny.',
            'is_active' => false,
        ]);

        ReportStatus::factory()->create([
            'name' => 'Status testowy',
            'slug' => 'test-status',
            'description' => 'Opis statusu testowego.',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.report-statuses.index'));

        $response
            ->assertOk()
            ->assertJson([
                'status' => 200,
            ])
            ->assertJsonMissing([
                'slug' => 'inactive-status',
            ]);

        $reportStatuses = $response->json('data');

        $this->assertIsArray($reportStatuses);
        $this->assertSame('duplicate', $reportStatuses[0]['slug'] ?? null);
        $this->assertSame('new', $reportStatuses[1]['slug'] ?? null);
        $this->assertSame('rejected', $reportStatuses[2]['slug'] ?? null);
        $this->assertSame('test-status', $reportStatuses[3]['slug'] ?? null);
        $this->assertSame('in-progress', $reportStatuses[4]['slug'] ?? null);
        $this->assertSame('confirmed', $reportStatuses[5]['slug'] ?? null);
        $this->assertSame('Opis statusu testowego.', $reportStatuses[3]['description'] ?? null);
        $this->assertArrayNotHasKey('id', $reportStatuses[0] ?? []);
        $this->assertArrayNotHasKey('is_active', $reportStatuses[0] ?? []);
    }
}
