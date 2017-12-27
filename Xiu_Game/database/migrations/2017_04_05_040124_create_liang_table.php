<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_liang',function (Blueprint $table){
            $table->increments('id')->comment('唯一标示');
            $table->unsignedInteger('liang')->default(0)->comment('靓号');
            $table->unsignedTinyInteger('state')->default(0)->comment('使用状态（0-未使用，1-已使用）');
            $table->unsignedInteger('olduid')->default(0)->comment('绑定的原uid');
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
        //Schema::dropIfExists('pp_agentsys_liang');
    }
}
