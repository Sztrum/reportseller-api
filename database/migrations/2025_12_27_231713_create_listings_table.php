<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'listings';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('platform_id')
                ->constrained('platforms')
                ->restrictOnDelete();

            $table->foreignUuid('report_category_id')
                ->constrained('report_categories')
                ->restrictOnDelete();

            $table->string('external_id')->nullable();
            $table->string('url', 768)->unique();
            $table->string('title')->nullable();
            $table->string('currency', 3)->default('PLN')->nullable();
            $table->string('seller_type')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('location')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->boolean('is_active_external')->default(true);
            $table->boolean('is_active')->default(false);

            $table->timestamps();

            $table->index('platform_id');
            $table->index(['platform_id', 'external_id']);
        });
    }
};
