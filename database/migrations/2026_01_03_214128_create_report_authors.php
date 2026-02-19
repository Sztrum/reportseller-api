<?php

use App\V1\Shared\Migrations\AbstractCreateTableMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends AbstractCreateTableMigration
{
    protected string $table_name = 'report_authors';

    public function up(): void
    {
        Schema::create($this->table_name, static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->char('ip_hash', 64)->nullable();
            $table->string('consent_version', 50)->nullable();
            $table->timestamp('consent_given_at')->nullable();

            $table->boolean('is_blocked')->default(false);
            $table->timestamp('blocked_at')->nullable();

            $table->timestamps();

            $table->index(['is_blocked', 'ip_hash']);
        });
    }
};
