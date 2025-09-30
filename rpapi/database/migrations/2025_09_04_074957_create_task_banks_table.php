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
        Schema::create('emp_i_task_bank_table', function (Blueprint $table) {
            $table->id('task_bank_id');
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->string('task_category')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_date')->useCurrent();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('task_banks');
    }
};
