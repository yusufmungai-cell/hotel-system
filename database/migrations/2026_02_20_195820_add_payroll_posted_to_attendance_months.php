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
    Schema::table('attendance_months', function (Blueprint $table) {
        $table->boolean('payroll_posted')->default(false);
        $table->timestamp('payroll_posted_at')->nullable();
    });
}

public function down()
{
    Schema::table('attendance_months', function (Blueprint $table) {
        $table->dropColumn(['payroll_posted','payroll_posted_at']);
    });
}
};
