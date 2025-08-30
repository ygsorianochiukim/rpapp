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
        Schema::create('emp_i_position', function (Blueprint $table) {
            $table->id('position_id')->primary();
            $table->string('position');
            $table->string('department');
            $table->string('function');
            $table->unsignedBigInteger('s_bpartner_employee_id');
            $table->timestamp('date_created');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active');
            $table->timestamps();

            // $table->foreign('s_bpartner_employee_id')
            //     ->references('s_bpartner_employee_id')
            //     ->on('s_bpartner_employee_table');

            // $table->foreign('created_by')
            //     ->references('s_bpartner_employee_id')
            //     ->on('s_bpartner_employee_table');
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
