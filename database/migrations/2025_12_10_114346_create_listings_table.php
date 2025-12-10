<?php

declare(strict_types=1);

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
            $table->foreignUuid('platform_id')->constrained('platforms')->restrictOnDelete();
            $table->foreignUuid('report_category_id')->nullable()->constrained('report_categories')->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('url')->unique();
            $table->string('title');
            $table->string('seller_type')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->string('currency', 3)->default('PLN');
            $table->string('location')->nullable();
            $table->string('thumbnail_url')->nullable();

            $table->timestamps();
        });
    }
};
