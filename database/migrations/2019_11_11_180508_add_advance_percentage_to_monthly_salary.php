<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvancePercentageToMonthlySalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_salary', function (Blueprint $table) {
            $table->integer('advance_percentage')->after('created_at');
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
            $table->dropColumn('advance_percentage');
        });
    }
}
