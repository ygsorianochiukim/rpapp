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
        Schema::table('emp_i_task_bank_table', function (Blueprint $table) {
            $table->string('repeat_frequency')->nullable();
            $table->string('remind_task')->nullable();
            $table->dateTime('date_remind')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_i_task_bank_table', function (Blueprint $table) {
            //
        });
    }
};
