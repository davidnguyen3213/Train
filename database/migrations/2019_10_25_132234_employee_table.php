<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('surname');
            $table->string('middle_name')->nullable();
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('work_number');
            $table->string('home_number');
            $table->date('date_of_birth');
            $table->string('work_email')->unique();
            $table->string('personal_email');
            $table->string('pincode')->nullable();
            $table->string('pincode_hint')->nullable();
            $table->date('joining_date');
            $table->date('leaving_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('Current: 1/ Suspended: 2/ Left: 3');
            $table->tinyInteger('is_active')->default(0)->comment('active: 1/ nonactive: 0');
            $table->string('activation_code')->nullable();
            $table->string('bank_account');
            $table->string('bank');
            $table->string('photo')->nullable();
            $table->string('id_number')->nullable();
            $table->string('address')->nullable();
            $table->string('personal_tax_code')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->tinyInteger('gender')->default(1)->comment('male: 1/ female: 2/ other: 3');
            $table->tinyInteger('marital_status')->default(0)->comment('married: 1/ single: 0');
            $table->tinyInteger('children')->default(0)->comment('yes: 1/ none: 0');
            $table->string('education')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
