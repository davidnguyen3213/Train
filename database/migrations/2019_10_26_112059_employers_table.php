<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('company');
            $table->string('vat_number');
            $table->string('first_contact');
            $table->string('fc_title')->nullable();
            $table->string('fc_number');
            $table->string('second_contact')->nullable();
            $table->string('sc_title')->nullable();
            $table->string('sc_number')->nullable();
            $table->string('address')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('website')->nullable();
            $table->integer('payroll_payment_date')->default(5);
            $table->integer('advance_all_salary_date')->default(25);
            $table->integer('advance_date_adjustment')->default(3);
            $table->tinyInteger('company_status')->default(1)->comment('1: current/ 2: stop');
            $table->tinyInteger('payroll_status')->default(1)->comment('1: current/ 2: stop');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employers');
    }
}
