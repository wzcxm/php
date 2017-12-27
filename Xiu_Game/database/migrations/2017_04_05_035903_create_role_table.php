<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_role',function (Blueprint $table){
            $table->increments('roleid')->comment('角色ID');
            $table->string('rname','20')->default(' ')->comment('角色名称');
            $table->string('remarks','100')->default(' ')->comment('角色说明');
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
        //Schema::dropIfExists('pp_agentsys_role');
    }
}
