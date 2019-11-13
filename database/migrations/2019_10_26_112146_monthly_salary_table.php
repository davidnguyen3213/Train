<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MonthlySalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_salary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index();
            $table->integer('employer_id')->index();
            $table->string('employee_number')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('weway_customer_number')->index();
            $table->string('contractual_salary')->default(0);
            $table->string('net_salary')->default(0);
            $table->integer('standard_working_days')->default(21);
            $table->integer('working_day_adjustment')->default(0);
            $table->integer('actual_working_days')->default(21);
            $table->integer('payroll_payment_date')->default(5);
            $table->integer('advance_all_salary_date')->default(25);
            $table->integer('advance_date_adjustment')->default(3);
            $table->string('year_month')->index();
            $table->unique(['employee_id', 'employer_id', 'year_month']);
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
        Schema::dropIfExists('monthly_salary');
    }
}
