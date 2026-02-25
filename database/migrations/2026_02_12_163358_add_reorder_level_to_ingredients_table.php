<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('ingredients', function (Blueprint $table) {
        $table->decimal('reorder_level', 10, 2)->default(0)->after('stock');
    });
}

public function down()
{
    Schema::table('ingredients', function (Blueprint $table) {
        $table->dropColumn('reorder_level');
    });
}

};
