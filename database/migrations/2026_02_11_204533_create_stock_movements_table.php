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
    Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('ingredient_id');
        $table->string('type'); // ISSUE, RECEIVE, ADJUSTMENT
        $table->decimal('qty', 10,2);

        $table->decimal('before_qty', 10,2);
        $table->decimal('after_qty', 10,2);

        $table->string('reference')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
