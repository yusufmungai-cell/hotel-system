<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->foreignId('department_id')->nullable()->after('name')->constrained();
            $table->foreignId('position_id')->nullable()->after('department_id')->constrained();
            $table->foreignId('employment_type_id')->nullable()->after('position_id')->constrained();

        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['employment_type_id']);

            $table->dropColumn(['department_id','position_id','employment_type_id']);

        });
    }
};
