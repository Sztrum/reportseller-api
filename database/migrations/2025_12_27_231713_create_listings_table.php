<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'listings';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('platform_id');
            $table->uuid('report_category_id');
            $table->string('external_id')->nullable();
            $table->string('url', 2048)->unique();
            $table->string('title')->nullable();
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('location')->nullable();
            $table->string('thumbnail_url', 2048)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('platform_id')
                ->references('id')
                ->on('platforms')
                ->restrictOnDelete();

            $table->foreign('report_category_id')
                ->references('id')
                ->on('report_categories')
                ->restrictOnDelete();

            $table->index('platform_id');
            $table->index(['platform_id', 'external_id']);
        });
    }
};
