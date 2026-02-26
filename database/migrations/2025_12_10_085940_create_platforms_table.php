<?php

declare(strict_types=1);

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'platforms';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('base_url')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('name');
            $table->index('base_url');
        });
    }
};
