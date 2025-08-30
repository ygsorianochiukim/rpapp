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
        Schema::create('task_i_information_table', function (Blueprint $table) {
            $table->id('task_i_information_id')->primary();
            $table->string('task_name');
            $table->longText('description');
            $table->string('task_category');
            $table->unsignedBigInteger('s_bpartner_employee_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_date');
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('s_bpartner_employee_id')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
            $table->foreign('created_by')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
