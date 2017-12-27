<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_menu',function (Blueprint $table){
            $table->increments('menuid')->comment('菜单ID');
            $table->string('name','20')->default(' ')->comment('菜单名称');
            $table->string('linkurl','50')->default(' ')->comment('菜单地址');
            $table->string('remarks','50')->default(' ')->comment('备注');
            $table->unsignedInteger('frontid')->default(0)->comment('上级菜单id');
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
        //Schema::dropIfExists('pp_agentsys_menu');
    }
}
