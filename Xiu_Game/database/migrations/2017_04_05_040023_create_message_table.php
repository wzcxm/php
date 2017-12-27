<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_message',function (Blueprint $table){
            $table->increments('msgid')->comment('信息ID');
            $table->unsignedTinyInteger('mgametype')->default(0)->comment('游戏类型(1-红中麻将，2-长沙麻将等)');
            $table->unsignedTinyInteger('mtype')->default(0)->comment('信息类型（1-跑马灯，2-活动公告，3-游戏规则，4-客服联系方式，等）');
            $table->text('mcontent')->comment('信息内容');
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
        //Schema::dropIfExists('pp_agentsys_message');
    }
}
