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
        Schema::table('task_i_information_table', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('position_id')->on('emp_i_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_i_information_table', function (Blueprint $table) {
            //
        });
    }
};
