<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayrollFieldsToEmployeesTable extends Migration
{
    public function up(): void
{
    Schema::table('employees', function (Blueprint $table) {

        if (!Schema::hasColumn('employees', 'employment_type')) {
            $table->enum('employment_type', ['casual', 'permanent'])
                  ->default('permanent')
                  ->after('position');
        }

        if (!Schema::hasColumn('employees', 'daily_rate')) {
            $table->decimal('daily_rate', 10,2)
                  ->nullable()
                  ->after('employment_type');
        }

        if (!Schema::hasColumn('employees', 'monthly_salary')) {
            $table->decimal('monthly_salary', 10,2)
                  ->nullable()
                  ->after('daily_rate');
        }

        if (!Schema::hasColumn('employees', 'leave_days')) {
            $table->integer('leave_days')
                  ->default(0)
                  ->after('monthly_salary');
        }
    });
}


    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'employment_type',
                'daily_rate',
                'monthly_salary',
                'leave_days'
            ]);
        });
    }
}
