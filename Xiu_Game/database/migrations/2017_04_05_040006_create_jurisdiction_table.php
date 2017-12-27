<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurisdictionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_jurisdiction',function (Blueprint $table){
            $table->increments('jurid')->comment('唯一标示');
            $table->unsignedInteger('roleid')->default(0)->comment('角色ID');
            $table->unsignedInteger('menuid')->default(0)->comment('菜单ID');
            $table->unique(['roleid', 'menuid'],'unique_rid_mid');
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
        //Schema::dropIfExists('pp_agentsys_jurisdiction');
    }
}
