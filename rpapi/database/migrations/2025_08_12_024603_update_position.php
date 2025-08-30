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
        Schema::table('emp_i_position', function (Blueprint $table) {
            $table->dropColumn('s_bpartner_employee_id');
            $table->foreign('created_by')
                ->references('s_bpartner_employee_id')
                ->on('s_bpartner_employee_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_i_position', function (Blueprint $table) {
            //
        });
    }
};
