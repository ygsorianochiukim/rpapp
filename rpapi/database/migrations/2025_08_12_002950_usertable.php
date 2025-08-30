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
        Schema::create('s_bpartner_employee_table', function (Blueprint $table) {
            $table->id('s_bpartner_employee_id')->primary();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('companyname');
            $table->string('sex');
            $table->string('employee_no');
            $table->string('marital_status');
            $table->date('birthdate');
            $table->longText('image_location')->nullable();
            $table->integer('remaining_leave')->nullable();
            $table->date('created')->nullable();
            $table->date('date_created')->nullable();
            $table->date('date_updated')->nullable();
            $table->date('updated')->nullable();
            $table->string('is_active');
            $table->integer('s_bpartner_id')->nullable();
            $table->integer('s_bpartner_employee_group_id')->nullable();
            $table->integer('s_bpartner_employee_id_apprived1st')->nullable();
            $table->integer('s_bpartner_employee_id_apprived2nd')->nullable();
            $table->bigInteger('sss_no')->nullable();
            $table->bigInteger('hdmf_no')->nullable();
            $table->bigInteger('phic_no')->nullable();
            $table->bigInteger('tin_no')->nullable();
            $table->bigInteger('contact_no')->nullable();
            $table->longText('address');
            $table->integer('s_bpartner_employee_id_revision')->nullable();
            $table->string('is_for_approval')->nullable();
            $table->string('email');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('paf_i_company_id')->nullable();
            $table->integer('created_by')->nullable();
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
