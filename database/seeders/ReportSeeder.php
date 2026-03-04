<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\V1\Modules\ListingCatalog\Listing\Domain\Models\Listing;
use App\V1\Modules\ReportCatalog\Report\Domain\Models\Report;
use App\V1\Modules\ReportCatalog\ReportAttachment\Domain\Models\ReportAttachment;
use App\V1\Modules\ReportCatalog\ReportAuthor\Domain\Models\ReportAuthor;
use App\V1\Modules\ReportCatalog\ReportStatus\Domain\Models\ReportStatus;
use Illuminate\Database\Seeder;

final class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ReportStatus::query()
            ->whereIn('slug', ['new', 'in-progress', 'confirmed', 'rejected', 'duplicate'])
            ->get()
            ->keyBy('slug');

        if ($statuses->count() < 5) {
            $this->call([
                ReportStatusSeeder::class,
            ]);

            $statuses = ReportStatus::query()
                ->whereIn('slug', ['new', 'in-progress', 'confirmed', 'rejected', 'duplicate'])
                ->get()
                ->keyBy('slug');
        }

        $listings = Listing::query()->inRandomOrder()->take(18)->get();
        if ($listings->isEmpty()) {
            $listings = collect([Listing::factory()->active()->create()]);
        }

        $authors = ReportAuthor::factory()->count(8)->consented('v1')->create()->values();
        $blockedAuthors = ReportAuthor::factory()->count(2)->blocked()->create()->values();
        $allAuthors = $authors->merge($blockedAuthors)->values();

        $statusPool = [
            ['id' => $statuses['new']->id, 'name' => 'Nowe'],
            ['id' => $statuses['new']->id, 'name' => 'Nowe'],
            ['id' => $statuses['in-progress']->id, 'name' => 'W trakcie'],
            ['id' => $statuses['confirmed']->id, 'name' => 'Potwierdzone'],
            ['id' => $statuses['rejected']->id, 'name' => 'Odrzucone'],
            ['id' => $statuses['duplicate']->id, 'name' => 'Duplikat'],
        ];

        $reasons = [
            'podejrzenie oszustwa finansowego',
            'niezgodna cena względem opisu',
            'podrobione zdjęcia produktu',
            'sprzedawca unika kontaktu po wpłacie',
            'oferta wygląda na duplikat z innego portalu',
            'opis zawiera sprzeczne informacje',
        ];
        $moderationNotes = [
            'Weryfikacja dowodów i historii zmian ogłoszenia.',
            'Sprawdzono metadane zdjęć oraz spójność opisu.',
            'Potwierdzono naruszenie regulaminu po analizie zgłoszeń.',
            'Brak wystarczających podstaw do potwierdzenia naruszenia.',
            'Scalono z wcześniejszym zgłoszeniem dotyczącym tego samego URL.',
        ];

        $counter = 1;
        foreach ($listings as $listing) {
            $reportsPerListing = random_int(1, 3);
            for ($i = 0; $i < $reportsPerListing; $i++) {
                $status = $statusPool[array_rand($statusPool)];
                $reason = $reasons[array_rand($reasons)];
                $author = $allAuthors->random();
                $isPublished = $status['name'] === 'Potwierdzone' && random_int(0, 1) === 1;
                $moderationNote = in_array($status['name'], ['W trakcie', 'Potwierdzone', 'Odrzucone', 'Duplikat'], true)
                    ? $moderationNotes[array_rand($moderationNotes)]
                    : null;
                $title = sprintf('Zgłoszenie #%03d: %s', $counter, ucfirst($reason));

            $report = Report::query()->updateOrCreate(
                    ['title' => $title],
                [
                    'listing_id' => $listing->id,
                        'report_status_id' => $status['id'],
                        'report_author_id' => $author->id,
                        'description' => sprintf(
                            'Użytkownik zgłasza ofertę "%s" (%s). Powód: %s. Prośba o moderację wpisu.',
                            (string) $listing->title,
                            (string) $listing->url,
                            $reason
                        ),
                        'moderation_note' => $moderationNote,
                        'is_published' => $isPublished,
                        'published_at' => $isPublished ? now()->subDays(random_int(0, 3)) : null,
                ],
            );

                ReportAttachment::query()->where('report_id', $report->id)->delete();

                $attachmentCount = in_array($status['name'], ['Potwierdzone', 'W trakcie'], true)
                    ? random_int(1, 3)
                    : random_int(0, 2);

                if ($attachmentCount > 0) {
                    ReportAttachment::factory()
                        ->count($attachmentCount)
                        ->image()
                        ->create([
                            'report_id' => $report->id,
                        ]);
                }

                $counter++;
            }
        }
    }
}
