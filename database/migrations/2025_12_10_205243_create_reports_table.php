<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'reports';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('listing_id')->constrained('listings')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('report_statuses')->restrictOnDelete();
            $table->string('reporter_email')->nullable();
            $table->text('description');
            $table->text('moderation_note')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index('listing_id');
            $table->index(['status_id', 'is_published', 'published_at']);
        });
    }
};
