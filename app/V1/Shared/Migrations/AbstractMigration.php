<?php

declare(strict_types=1);

namespace App\V1\Shared\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

abstract class AbstractMigration extends Migration
{
    protected string $table_name;

    abstract public function up(): void;
    abstract public function down(): void;

    public function hasForeignKey(string $table, string $column): bool
    {
        $foreignKeys = Schema::getConnection()
            ->getSchemaBuilder()
            ->getForeignKeys($table);

        return collect($foreignKeys)
            ->flatMap(static fn (array $foreignKey): array => $foreignKey['columns'] ?? [])
            ->contains($column);
    }
}
