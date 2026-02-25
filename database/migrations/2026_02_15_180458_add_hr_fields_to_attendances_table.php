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
        Schema::table('attendances', function (Blueprint $table) {

    // System suggestion (automatic)
    $table->string('suggested_status')->nullable();
    $table->decimal('suggested_overtime_hours',5,2)->nullable();

    // HR final decision
    $table->string('hr_status')->nullable(); 
    $table->decimal('hr_overtime_hours',5,2)->nullable();

    $table->unsignedBigInteger('hr_approved_by')->nullable();
    $table->timestamp('hr_approved_at')->nullable();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
        });
    }
};
