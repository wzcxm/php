<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//微信支付回调地址
//Route::post('/notify','BuyBubbleController@notify');


//玩家购买回调
Route::any('/player/notify','CashController@notify');

//app支付回调
Route::any('/app/notify','GameSericeController@appnotify');

//苹果支付校验
Route::any('/applepaycheck','GameSericeController@applePayCheck');



//获取玩家的抽奖记录
Route::any('/GetRadList/{uid}','GameSericeController@GetRadList');

//获取玩家的领取记录
Route::any('/GetObainList/{uid}','GameSericeController@GetObainList');

//抽奖
Route::any('/GetLottery/{uid}','GameSericeController@GetLotteryItem');
