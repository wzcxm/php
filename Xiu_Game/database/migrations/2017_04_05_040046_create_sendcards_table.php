<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_sendcards',function (Blueprint $table){
            $table->increments('wid')->comment('赠送ID');
            $table->unsignedInteger('uid')->default(0)->comment('玩家ID');
            $table->unsignedTinyInteger('stype')->default(0)->comment('送卡对象类型,1-个人，2-个人及下属，3-全服');
            $table->unsignedInteger('speople')->default(0)->comment('人数');
            $table->unsignedInteger('sbubble')->default(0)->comment('房卡数');
            $table->timestamp('createdate')->comment('赠送时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        //Schema::dorpIfExists('pp_agentsys_sendcards');
    }
}
