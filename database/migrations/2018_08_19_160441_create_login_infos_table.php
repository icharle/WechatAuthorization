<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scene')->index()->comment('场景值');
            $table->string('openId_id')->default('0')->comment('用户唯一值');
            $table->tinyInteger('status')->default(0)->comment('状态值 0为未使用状态 1为授权状态 2为拒接状态');
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
        Schema::dropIfExists('login_infos');
    }
}
