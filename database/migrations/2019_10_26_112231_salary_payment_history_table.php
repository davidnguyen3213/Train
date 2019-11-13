<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalaryPaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_payment_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_id')->index();
            $table->date('date');
            $table->string('year_month')->index();
            $table->string('advance')->nullable();
            $table->string('total_advance')->nullable();
            $table->string('net_amount')->nullable();
            $table->string('payment')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('salary_payment_history');
    }
}
