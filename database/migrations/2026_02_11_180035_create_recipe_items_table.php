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
    Schema::create('recipe_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('recipe_id')->constrained();
        $table->foreignId('ingredient_id')->constrained();

        $table->decimal('qty', 10, 2);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('recipe_items');
}

};
