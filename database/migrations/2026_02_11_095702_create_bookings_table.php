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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('guest_id')->constrained();
        $table->foreignId('room_id')->constrained();

        $table->date('check_in');
        $table->date('check_out')->nullable();

        $table->decimal('daily_rate', 10, 2);

        $table->decimal('total', 10, 2)->default(0);

        $table->string('status')->default('checked_in');

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('bookings');
}

};
