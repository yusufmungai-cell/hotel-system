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
        Schema::create('attendance_months', function (Blueprint $table) {
    $table->id();
    $table->integer('month');
    $table->integer('year');
    $table->enum('status',['open','approved'])->default('open');
    $table->unsignedBigInteger('approved_by')->nullable();
    $table->timestamp('approved_at')->nullable();
    $table->unsignedBigInteger('reopened_by')->nullable();
    $table->timestamp('reopened_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_months');
    }
};
