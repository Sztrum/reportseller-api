<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'reports';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('listing_id')
                ->constrained('listings')
                ->restrictOnDelete();

            $table->foreignUuid('report_status_id')
                ->constrained('report_statuses')
                ->restrictOnDelete();

            $table->foreignUuid('report_author_id')
                ->constrained('report_authors')
                ->restrictOnDelete();

            $table->text('description');
            $table->text('moderation_note')->nullable();
            $table->boolean('is_published')->default(false);

            $table->timestamp('published_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->index('listing_id');

            $table->index(['report_status_id', 'is_published'], 'reports_status_pub_idx');
            $table->index(['published_at', 'deleted_at'], 'reports_dates_idx');
        });
    }
};
