<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('receiving_items', function (Blueprint $table) {
        $table->foreignId('ingredient_id')
              ->after('receiving_id')
              ->constrained()
              ->cascadeOnDelete();
    });
}

public function down(): void
{
    Schema::table('receiving_items', function (Blueprint $table) {
        $table->dropForeign(['ingredient_id']);
        $table->dropColumn('ingredient_id');
    });
}

};
