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
    Schema::create('receiving_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('receiving_id')->constrained();
        $table->string('item_name');

        $table->integer('qty');
        $table->decimal('price', 10, 2);
        $table->decimal('total', 10, 2);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('receiving_items');
}

};
