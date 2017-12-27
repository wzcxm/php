<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardstradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_agentsys_cardstrade',function (Blueprint $table){
            $table->increments('cid')->comment('交易ID');
            $table->unsignedInteger('cbuyid')->default(0)->comment('购买用户uid');
            $table->unsignedInteger('csellid')->default(0)->comment('出售用户uid');
            $table->integer('cnumber')->default(0)->comment('购买数量');
            $table->unsignedTinyInteger('ctype')->default(0)->comment('交易类型,1-购币,2-赠送,3-系统奖励,4-返币');
            $table->timestamp('ctradedate')->comment('交易日期');
            $table->unique(['ctradedate', 'csellid', 'cbuyid', 'ctype'],'index_ctradedate_csellid_cbuyid_ctype');
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
        //Schema::dropIfExists('pp_agentsys_cardstrade');
    }
}
