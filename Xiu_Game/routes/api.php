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
