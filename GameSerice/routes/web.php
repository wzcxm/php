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

//获取玩家信息
$router->get('/getPlayer/{uid}/{sign}','GameSericeController@GetPlayer');

//获取玩家的茶楼列表
$router->get('/getTeaList/{uid}/{sign}','GameSericeController@GetTeaList');

//获取茶楼玩家列表
$router->get('/getTeaPlayerList/{teaid}/{sign}','GameSericeController@GetTeaPlayerList');

//版本控制
$router->get('/V/{version}/{type}/{res}/{src}','GameSericeController@GetVersion');


