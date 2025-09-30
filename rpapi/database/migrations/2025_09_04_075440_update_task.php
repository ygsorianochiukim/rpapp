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
            $table->unsignedBigInteger('task_bank_id')->nullable()->after('task_i_information_id');
            $table->foreign('task_bank_id')->references('task_bank_id')->on('emp_i_task_bank_table')->nullOnDelete();
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
