<?php

declare(strict_types=1);

namespace Database\Factories;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
final class ReportFactory extends Factory
{
    /** @var class-string<Report> */
    protected $model = Report::class;

    public function definition(): array
    {
        $reasons = [
            'Podejrzenie oszustwa',
            'Niezgodna cena',
            'Wprowadzający opis',
            'Duplikat ogłoszenia',
            'Fałszywe zdjęcia',
        ];
        $reason = $this->faker->randomElement($reasons);

        return [
            'listing_id' => Listing::factory(),
            'report_status_id' => ReportStatus::factory()->state([
                'slug' => 'new',
                'name' => 'Nowe',
                'is_active' => true,
            ]),
            'report_author_id' => ReportAuthorFactory::new(),
            'title' => $reason . ' - zgłoszenie',
            'description' => sprintf(
                'Zgłoszenie użytkownika dotyczące ogłoszenia. Powód: %s. Prośba o sprawdzenie wiarygodności oferty.',
                mb_strtolower($reason)
            ),
            'moderation_note' => null,
            'is_published' => false,
            'published_at' => null,
            'deleted_at' => null,
        ];
    }

    public function published(): self
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function inProgress(): self
    {
        return $this->withStatus('in-progress', 'W trakcie');
    }

    public function confirmed(): self
    {
        return $this->withStatus('confirmed', 'Potwierdzone');
    }

    public function rejected(): self
    {
        return $this->withStatus('rejected', 'Odrzucone');
    }

    public function duplicate(): self
    {
        return $this->withStatus('duplicate', 'Duplikat');
    }

    public function withModerationNote(string $note = 'Weryfikacja moderatorska w toku.'): self
    {
        return $this->state([
            'moderation_note' => $note,
        ]);
    }

    public function deleted(): self
    {
        return $this->state([
            'deleted_at' => now(),
        ]);
    }

    private function withStatus(string $slug, string $name): self
    {
        return $this->state([
            'report_status_id' => ReportStatus::factory()->state([
                'slug' => $slug,
                'name' => $name,
                'is_active' => true,
            ]),
        ]);
    }
}
