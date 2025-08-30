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
        Schema::create('team_i_access_table', function (Blueprint $table) {
            $table->id('team_access_id');
            $table->unsignedBigInteger('s_bpartner_employee_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_date');
            $table->timestamps();

            $table->foreign('s_bpartner_employee_id')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('supervisor_id')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('created_by')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_accesses');
    }
};
