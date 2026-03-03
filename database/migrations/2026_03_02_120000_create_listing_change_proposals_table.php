<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'listing_change_proposals';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            $table->foreignUuid('report_id')
                ->nullable()
                ->constrained('reports')
                ->nullOnDelete();

            $table->string('status', 20);
            $table->json('current_payload');
            $table->json('proposed_payload');
            $table->timestamp('requested_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->text('review_reason')->nullable();
            $table->timestamps();

            $table->index(['listing_id', 'status'], 'listing_change_proposals_listing_status_idx');
        });
    }
};
