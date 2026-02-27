<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'listing_histories';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            $table->string('external_id')->nullable();
            $table->string('url', 768);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('seller_type')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('location')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->boolean('is_active_external')->nullable();
            $table->timestamp('captured_at');

            $table->timestamps();

            $table->index(['listing_id', 'captured_at']);
        });
    }
};
