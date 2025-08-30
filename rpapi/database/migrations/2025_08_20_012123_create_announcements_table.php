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
        Schema::create('announcements_i_table', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('announcement_description');
            $table->date('date_validity');
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_date');
            $table->timestamps();

            $table->foreign('created_by')->references('s_bpartner_employee_id')->on('s_bpartner_employee_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
