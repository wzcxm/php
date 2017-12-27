<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingmallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_shoppingmall',function (Blueprint $table){
            $table->increments('sid')->comment('商品ID');
            $table->string('scommodity','20')->default(' ')->comment('商品名称');
            $table->integer('sprice')->default(0)->comment('价格');
            $table->unsignedInteger('snumber')->default(0)->comment('数量');
            $table->unsignedInteger('sgive')->default(0)->comment('赠送数量');
            $table->string('sremarks','60')->default(' ')->comment('备注');
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
        //Schema::dropIfExists('pp_agentsys_shoppingmall');
    }
}
