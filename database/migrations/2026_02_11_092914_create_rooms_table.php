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
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_type_id')->constrained();
        $table->string('room_number');
        $table->string('status')->default('available'); // available, occupied, maintenance
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('rooms');
}

};
