<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
//登录
$router->get('/login/{uid}&{type}&{value}','GameLoginController@login');

//获取玩家的茶楼列表
$router->get('/getTeaList/{uid}/{sign}','GameSericeController@GetTeaList');

//获取茶楼排行榜
$router->get('/getTeaOrderBy/{sign}','GameSericeController@GetTeaOrderByList');

//获取茶楼玩家列表
$router->get('/getTeaPlayerList/{teaid}/{sign}','GameSericeController@GetTeaPlayerList');

//添加修改茶楼成员备注
$router->get('/updateRemark/{teaid}/{uid}/{remark}/{sign}','GameSericeController@updateRemark');

//获取茶楼经营情况
$router->get('/getBusList/{teaid}/{sign}','GameSericeController@getBusList');

//版本控制
$router->get('/V/{version}/{type}','GameSericeController@GetVersion');

//下载地址
$router->get('/GetUrl',function (){
    return ['android'=>'http://fir.im/ysrn',
        'ios'=>'https://pro-app-tc.fir.im/55ad19b179b374052dea4d83005d62ca11c49e1c.apk?sign=8b33da59c1d6fcb7925ab828131e9649&t=5aa9d16a',
        'resources'=>'http://cspp-collection.oss-cn-shenzhen.aliyuncs.com/update_package/',
        'files'=>'AB,resources.ab,image.ab,lua.ab'] ;
});

//获取回放数据
$router->get('/Playback/{gtype}/{rid}/{sign}','GameSericeController@getPlayback');

//总战绩
$router->get('/record/{uid}/{offset}/{sign}','GameSericeController@GetRecord');
//单局战绩
$router->get('/single/{roomid}/{time}/{g_type}/{offset}/{sign}','GameSericeController@BigRecord');


//短信验证码接口
$router->get('/sms/{tel}','GameSericeController@sendCodeSms');

//获取表情价格
$router->get('/phiz/{uid}/{sign}','GameSericeController@getPhiz');

//更新分享标识
$router->get('/setshare/{uid}/{sign}','GameSericeController@upLottery');

//保存手机号
$router->get('/uptel/{uid}/{tel}/{code}/{sign}','GameSericeController@upTel');

//获取红包金额
$router->get('/getred/{uid}/{sign}','GameSericeController@getRedBag');

//获取红包记录
$router->get('/redlist/{uid}/{sign}','GameSericeController@getRedList');

//更换微信号
$router->get('/setwx/{uid}/{code}/{sign}','GameSericeController@setWxinfo');

//茶楼总战绩
$router->get('/tearec/{teaid}/{offset}/{sign}','GameSericeController@getTeaRec');

//茶楼我的战绩
$router->get('/teamyrec/{teaid}/{uid}/{offset}/{sign}','GameSericeController@getMyTeaRec');

//中奖记录
$router->get('/winnlist/{uid}/{sign}','GameSericeController@getWinnList');

$router->get('/isformal/{version}',function ($version){
    if($version == 2.9){
        return 1;
    }else{
        return 0;
    }
});
