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
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('s_bpartner_employee_id');
            $table->unsignedBigInteger('task_i_information_id');
            $table->date('TaskDateComplete');
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_date');
            $table->timestamps();

            $table->foreign('s_bpartner_employee_id')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('task_i_information_id')->references('task_i_information_id')->on('task_i_information_table');
            $table->foreign('created_by')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
