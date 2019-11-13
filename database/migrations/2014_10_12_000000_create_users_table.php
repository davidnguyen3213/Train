<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->index();
            $table->string('password');
            $table->tinyInteger('status')->default(1)->comment('1: enable; 0: disable');
            $table->tinyInteger('type')->default(0)->comment('1: admin; 0: other');
            $table->tinyInteger('is_deleted')->default(0)->comment('1: yes; 0: no');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
