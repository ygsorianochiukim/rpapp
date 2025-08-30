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
        Schema::create('emp_i_user_access', function (Blueprint $table) {
            $table->uuid('emp_i_user_access_id')->primary();
            $table->unsignedBigInteger('s_bpartner_employee_id');
            $table->unsignedBigInteger('position_id');
            $table->timestamp('date_created');
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('s_bpartner_employee_id')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('position_id')->references('position_id')->on('emp_i_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_i_user_access', function (Blueprint $table) {
            //
        });
    }
};
