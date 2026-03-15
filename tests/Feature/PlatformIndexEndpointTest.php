<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\V1\Modules\Platform\Domain\Models\Platform;
use Database\Seeders\PlatformSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformIndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_only_active_platforms_sorted_by_order(): void
    {
        $this->seed([
            PlatformSeeder::class,
        ]);

        Platform::factory()->create([
            'name' => 'Nieaktywna platforma',
            'slug' => 'inactive-platform',
            'order' => 0,
            'is_active' => false,
        ]);

        Platform::factory()->create([
            'name' => 'Platforma testowa',
            'slug' => 'test-platform',
            'base_url' => 'https://test-platform.example',
            'order' => 2,
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.platforms.index'));

        $response
            ->assertOk()
            ->assertJson([
                'status' => 200,
            ])
            ->assertJsonMissing([
                'slug' => 'inactive-platform',
            ]);

        $platforms = $response->json('data');

        $this->assertIsArray($platforms);
        $this->assertSame('olx', $platforms[0]['slug'] ?? null);
        $this->assertSame('allegro', $platforms[1]['slug'] ?? null);
        $this->assertSame('test-platform', $platforms[2]['slug'] ?? null);
        $this->assertArrayNotHasKey('id', $platforms[0] ?? []);
        $this->assertArrayNotHasKey('order', $platforms[0] ?? []);
        $this->assertArrayNotHasKey('is_active', $platforms[0] ?? []);
    }
}
