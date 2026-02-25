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
    Schema::create('production_requests', function (Blueprint $table) {
        $table->id();

        $table->string('department')->default('Kitchen');

        $table->string('status')->default('pending');

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('production_requests');
}

};
