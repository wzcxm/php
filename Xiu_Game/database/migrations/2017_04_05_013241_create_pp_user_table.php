<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pp_user',function (Blueprint $table){
            $table->increments('uid')->comment('玩家ID');
            $table->string('uphone','11')->nullable()->default(' ')->comment('手机号码');
            $table->string('wechat','60')->nullable()->default(' ')->comment('微信号');
            $table->string('realname','30')->nullable()->default(' ')->comment('真实姓名');
            $table->string('idnum','18')->nullable()->default(' ')->comment('身份证号');
            $table->string('pwd','50')->nullable()->default(' ')->comment('密码');
            $table->string('head_img_url','200')->nullable()->default(' ')->comment('头像地址');
            $table->string('address','200')->nullable()->default(' ')->comment('用户所在地区');
            $table->unsignedInteger('roleid')->nullable()->default(5)->comment('角色ID');
            $table->unsignedInteger('front_uid')->nullable()->default(0)->comment('上级ID');
            $table->unsignedInteger('chief_uid')->nullable()->default(0)->comment('总代ID');
            $table->string('nickname','30')->nullable()->default(' ')->comment('昵称');
            $table->unsignedInteger('backcard')->nullable()->default(0)->comment('返卡次数');
            $table->unsignedInteger('integral')->nullable()->default(0)->comment('积分');
            $table->unsignedInteger('gold')->nullable()->default(0)->comment('房卡');
            $table->integer('scores')->nullable()->default(0)->comment('上下分数');
            $table->unsignedInteger('recharge_uid')->nullable()->default(0)->comment('上分人ID');
            $table->unsignedTinyInteger('freeze')->nullable()->default(0)->comment('用户状态（1-冻结,2-解冻）');
            $table->timestamp('create_time')->comment('创建日期');
            $table->integer('upper_limit')->nullable()->default(0)->comment('上限');
            $table->integer('lower_limit')->nullable()->default(0)->comment('下限');
            $table->integer('sex')->nullable()->default(0)->comment('性别');
            $table->dateTime('access_time')->nullable()->comment('最近访问时间');
            $table->dateTime('last_login_time')->nullable()->comment('上次登陆时间');
            $table->string('voip','50')->nullable()->comment('语音账号');
            $table->string('voip_pass','50')->nullable()->comment('语音账号密码');
            $table->string('account_sid','50')->nullable()->comment('语音账号Sid');
            $table->string('account_token','50')->nullable()->comment('语音账号token');
            $table->string('unionid','50')->nullable()->comment('微信unionid');
            $table->string('openid','50')->nullable()->comment('微信openid');
            $table->integer('money')->nullable()->comment('RMB');
            $table->integer('state')->nullable()->default(0)->comment('状态');
            $table->integer('flag')->nullable()->default(0)->comment('首冲等活动标签');
            $table->unique(['uid', 'front_uid', 'chief_uid'],'uid_front_uid_chief_uid');
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
        //Schema::dropIfExists('pp_user');
    }
}
