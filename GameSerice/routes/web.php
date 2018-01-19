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
    return ['android'=>'http://wyhq.oss-cn-beijing.aliyuncs.com/android/wyhq.apk',
        'ios'=>'http://fir.im/4zn2',
        'resources'=>'http://cspp-collection.oss-cn-shenzhen.aliyuncs.com/update_package/',
        'files'=>'AB,resources.ab,image.ab,lua.ab'] ;
});

//获取回放数据
$router->get('/Playback/{gtype}/{rid}/{sign}','GameSericeController@getPlayback');

//总战绩
$router->get('/record/{uid}/{g_type}/{sign}','GameSericeController@GetRecord');
//单局战绩
$router->get('/bigrecord/{roomid}/{time}/{g_type}/{sign}','GameSericeController@BigRecord');


//短信接口
$router->get('/sms/{uid}/{type}','GameSericeController@sendSms');

//获取表情价格
$router->get('/phiz/{uid}/{sign}','GameSericeController@getPhiz');

//更新分享标识
$router->get('/lottery/{uid}/{sign}','GameSericeController@upLottery');