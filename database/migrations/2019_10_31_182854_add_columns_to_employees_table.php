<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('weway_customer_number')->index()->after('id');
            $table->integer('employer_id')->index()->after('bank');
            $table->string('employee_number')->nullable()->after('employer_id');
            $table->integer('department_id')->nullable()->after('employee_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('weway_customer_number');
            $table->dropColumn('employer_id');
            $table->dropColumn('employee_number');
            $table->dropColumn('department_id');
        });
    }
}
