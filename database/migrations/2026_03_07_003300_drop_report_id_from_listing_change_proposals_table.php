<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_change_proposals', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('report_id');
        });
    }

    public function down(): void
    {
        Schema::table('listing_change_proposals', static function (Blueprint $table) {
            $table->foreignUuid('report_id')
                ->nullable()
                ->after('listing_id')
                ->constrained('reports')
                ->nullOnDelete();
        });
    }
};
