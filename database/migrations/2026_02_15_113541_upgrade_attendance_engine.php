<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            // remove old computed column
            if (Schema::hasColumn('attendances', 'hours_worked')) {
                $table->dropColumn('hours_worked');
            }

            // engine columns
            $table->decimal('work_hours',5,2)->nullable()->after('time_out');
            $table->decimal('overtime_hours',5,2)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->boolean('is_full_day')->default(false);
            $table->string('status')->default('present'); 
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('hours_worked',5,2)->nullable();
            $table->dropColumn(['work_hours','overtime_hours','late_minutes','is_full_day','status']);
        });
    }
};
