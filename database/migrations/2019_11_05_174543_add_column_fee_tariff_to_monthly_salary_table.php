<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFeeTariffToMonthlySalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->integer('fee_tariff')->default(0)->after('advance_date_adjustment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->dropColumn('fee_tariff');
        });
    }
}
