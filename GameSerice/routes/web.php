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
Route::get('/Playback/{gtype}/{rid}/{sign}','GameSericeController@getPlayback');

//总战绩
Route::get('/record/{uid}/{g_type}/{sign}','GameSericeController@GetRecord');
//单局战绩
Route::get('/bigrecord/{roomid}/{time}/{g_type}/{sign}','GameSericeController@BigRecord');


