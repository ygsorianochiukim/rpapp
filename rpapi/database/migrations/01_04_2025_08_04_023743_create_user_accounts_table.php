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
        // Schema::create('emp_i_user', function (Blueprint $table) {
        //     $table->uuid('s_bpartner_employee_id')->primary();
        //     $table->string('first_name');
        //     $table->string('middle_name');
        //     $table->string('last_name');
        //     $table->string('suffix')->nullable();
        //     $table->date('birthdate');
        //     $table->string('sex');
        //     $table->integer('contact_number');
        //     $table->string('company_name');
        //     $table->string('address');
        //     $table->string('email')->unique();
        //     $table->string('password')->unique();
        //     $table->boolean('is_active');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
