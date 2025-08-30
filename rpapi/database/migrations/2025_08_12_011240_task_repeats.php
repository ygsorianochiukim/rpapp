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
        Schema::create('task_i_repeats_table', function (Blueprint $table) {
            $table->id('task_repeat_id')->primary();
            $table->unsignedBigInteger('task_i_information_id');
            $table->string('repeat_frequency');
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_date');
            $table->timestamps();

            $table->foreign('task_i_information_id')->references('task_i_information_id')->on('task_i_information_table');
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
