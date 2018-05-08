<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function(){
    return view('UserLogin.WxLogin');
});
Route::get('/wxlogin','UserLoginController@index');
Route::get('/admin',function(){
    return view('UserLogin.Login');
});

Route::post('/Login','UserLoginController@Login');
Route::post('/phoneLogin','UserLoginController@phoneLogin');

Route::get('/Warning',function (){
    return view('UserLogin.Warning');
});

Route::group(['prefix' => '','middleware' => 'authuser'],function (){
    //首页
    Route::get('/Home','HomeController@index');
    //绑定手机
    Route::post('/Home/bindPhone','HomeController@updatePhone');

    //菜单
    Route::get('/Menus',function (){
        return view('Menus.Index');
    });
    Route::get('/Menus/add/{id?}','MenusController@create');
    Route::post('/Menus/data','MenusController@getData');
    Route::post('/Menus/save','MenusController@store');
    Route::post('/Menus/del/{id}','MenusController@destroy');
    //角色
    Route::get('/Role',function (){
        return view('Role.index');
    });
    Route::get('/Role/add/{id?}','RoleController@create');
    Route::post('/Role/data','RoleController@getData');
    Route::post('/Role/save','RoleController@store');
    Route::post('/Role/del/{id}','RoleController@destroy');
    //权限设置
    Route::get('/Power',function (){
        return view('Role_Menu.index');
    });
    Route::post('/Power/save','JurController@save');
    Route::post('/Power/role','JurController@getRole');
    Route::post('/Power/menu','JurController@getMenus');
    Route::get('/Power/getPower/{rid}','JurController@getPower');
    //参数设置
    Route::get('/Parameter','ParamController@index');
    Route::post('/Parameter','ParamController@store');
    //信息编辑
    Route::get('/Message',function (){
        return view('Message.index');
    });
    Route::get('/Message/add/{id?}','MessageController@create');
    Route::post('/Message/data','MessageController@getData');
    Route::post('/Message/save','MessageController@store');
    Route::post('/Message/del/{id}','MessageController@destroy');
    //商品设置
    Route::get('/ShoppingMall',function (){
        return view('ShoppingMall.index');
    });
    Route::get('/ShoppingMall/add/{id?}','ShoppingMallController@create');
    Route::post('/ShoppingMall/data','ShoppingMallController@getData');
    Route::post('/ShoppingMall/save','ShoppingMallController@store');
    Route::post('/ShoppingMall/del/{id}','ShoppingMallController@destroy');

    //我的代理
    Route::get('/MyAgent','MyAgentController@myAgent');
    Route::post('/MyAgent/data','MyAgentController@getData');
    //我的玩家
    Route::get('/MyPlayer','MyAgentController@myPlayer');
    Route::post('/MyPlayer/data','MyAgentController@getPlayer');

    //充卡
    Route::get('/BuyCard','BuyCardController@index');
    Route::get('/BuyCard/Search/{uid}','BuyCardController@Search');
    Route::post('/BuyCard/Gift','BuyCardController@PayerBuy');
    Route::get('/BuyCard/list',function (){
        return view('BuyCard.giverec');
    });
    Route::post('/BuyCard/giverec','BuyCardController@GiveRec');

    //我的信息
    Route::get('/MyInfo','MyInfoController@index');
    Route::post('/MyInfo/Save','MyInfoController@save');
    //活动公告
    //Route::get('/Notice','MyInfoController@notice');

    //返利统计
    Route::get('/BackReport',function (){
        return view('MyInfo.backreport');
    });
    Route::post('/BackReport/data','BackCashController@getData');

    //订单查询
    Route::get('/BuySearch',"CashController@search");
    Route::post('/BuySearch/data',"CashController@getData");

    //返利查询
    Route::get('/BackCash','BackCashController@index');
    Route::post('/BackCash/data','BackCashController@getData');
    //提现
    Route::get('/Extract','BackCashController@take');
//    //初次登录设置密码
//    Route::get('/Extract/first',function (){
//        return view('CashBuy.first');
//    });
//    Route::post('/Extract/first/save','BackCashController@savepwd');
//    //输入提现密码，登录
//    Route::get('/Extract/login',function (){
//        return view('CashBuy.login');
//    });
//    Route::post('/Extract/login/submit','BackCashController@login');
//    Route::get('/Extract/index','BackCashController@take');
    Route::post('/Extract/ext','BackCashController@ext');
    Route::get('/Extract/extlist',function (){
        return view('CashBuy.ext_list');
    });
    Route::post('/BackCash/getlist','BackCashController@extlist');

    //删除代理
    Route::get('/AgentDel',function (){
        return view('AgentManage.delete');
    });
    Route::post('/AgentDel/data','AgentManageController@getAgent');
    Route::post('/AgentDel/delete','AgentManageController@delete');

    //玩家列表
    Route::get('/Players',function (){
        return view('UserManage.index');
    });
    Route::post('/Players/data','UserManageController@getData');
    Route::post('/Players/lock','UserManageController@Lock');
    Route::post('/Players/unlock','UserManageController@Unlock');

    Route::post('/Players/setRole','UserManageController@setRole');

    //牌馆设置
    Route::get('/Museum',function (){
        return view('Museum.index');
    });
    Route::post('/Museum/data','MuseumController@getData');
    Route::get('/Museum/setting/{teaid}','MuseumController@setting');
    Route::post('/Museum/save','MuseumController@save_set');


    //游戏详情
    Route::get('/System','MyInfoController@getSysInfo');
});
/// end//////////////////////////////

///////游戏api////////////////////////

//我的推广码
Route::get('/MyQrCode','MyInfoController@getQrCode');

//玩家购买钻石
Route::get('/PlayerBuy/buy','CashController@buycard');
Route::post('/PlayerBuy/delno','CashController@delNo');
Route::get('/PlayerBuy/index','CashController@index');
Route::get('/PlayerBuy/list/{uid}','CashController@buylist');
Route::get('/PlayerBuy/getnick/{uid}','CashController@getnick');

//更新微信
Route::get('/UpdateWx','HomeController@updateWx');
//分享
Route::get('/share/{roomNo?}/{msg?}','GameSericeController@share');

//抽奖
Route::get('/lottery/{uid}','GameSericeController@getLottery');

//发红包
Route::get('/redpack/{uid}','GameSericeController@RedPack');

//游戏下载页面
Route::get('/download/{uid?}','GameSericeController@Download');

//购买金豆
Route::get('/buybeans/{uid}/{bid}','GameSericeController@buyBeans');

//验证码发送
Route::get('/sms/{tel}','GameSericeController@sendCodeSms');

//app支付统一下单
Route::get('/apppay/{uid}/{goods}','GameSericeController@getAppOrder');
//删除订单
Route::get('/delorder/{order_no}','GameSericeController@delAppOrder');



//Route::get('/testLottery','GameSericeController@testLottery');

/// end//////////////////////////////