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
            $table->string('openId')->unique()->index()->comment('用户唯一值');
            $table->string('nickName')->comment('用户昵称');
            $table->string('avatarUrl')->comment('用户头像');
            $table->tinyInteger('gender')->default(0)->comment('男女性别 0为女 1为男');
            $table->string('city')->comment('城市');
            $table->string('province')->comment('省份');
            $table->string('country')->comment('国家');
            $table->string('language')->comment('语言');
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
