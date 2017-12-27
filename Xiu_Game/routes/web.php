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

//Route::get('/',function (){
//    return view('welcome');
//});

Route::get('/','UserLoginController@index');
//Route::get('/index','UserLoginController@index');

Route::post('/Login','UserLoginController@Login');
Route::get('/Warning',function (){
    return view('UserLogin.Warning');
});

Route::group(['prefix' => '','middleware' => 'authuser'],function (){
    //首页
    Route::get('/Home','HomeController@index');
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
    Route::get('/MyAgent',function (){
        return view('MyAgent.MyAgent');
    });
    Route::post('/MyAgent/data','MyAgentController@getData');
    //我的玩家
    Route::get('/MyPlayer',function (){
        return view('MyAgent.MyService');
    });
    Route::post('/MyPlayer/data','MyAgentController@getPlayer');


    //充卡
    Route::get('/BuyCard','BuyCardController@index');
    Route::get('/BuyCard/Search/{uid}','BuyCardController@Search');
    Route::post('/BuyCard/{uid}/{number}','BuyCardController@PayerBuy');
    Route::get('/BuyCard/QueryBuy/{uid}/{number}','BuyCardController@querybuy');

//    //购卡、售卡、返卡、奖励统计(查询)
//    Route::get('/BuyQuery','CardQueryController@buy');//购卡
//    Route::get('/SellQuery','CardQueryController@sell');//售卡
//    Route::get('/BackQuery','CardQueryController@back');//返卡
//    Route::get('/RewardQuery','CardQueryController@reward');//奖励
    //Route::get('/CardQuery/Search','CardQueryController@Search');//查询

//    //上下分
//    Route::get('/UpAndDown','UpAndDownController@index');//列表
//    Route::get('/UpAndDown/Search/{uid}','UpAndDownController@search');//查询
//    Route::get('/UpAndDown/Up/{uid}','UpAndDownController@up');//上分
//    Route::get('/UpAndDown/Down/{uid}','UpAndDownController@down');//下分
//    Route::post('/UpAndDown/Save','UpAndDownController@save');//保存分数
//    //战绩统计
//    Route::get('/White','WhiteController@indexlist');
//    Route::get('/White/Set','WhiteController@index');//分数设置
//    Route::get('/White/Record',function (){   //战绩统计
//        return view('White.record');
//    });
//    Route::get('/White/RecordSearch','WhiteController@recordSearch');
//    Route::get('/White/Tops',function (){
//        return view('White.tops');
//    });//战绩排行
//    Route::get('/White/Tops/Search/{type}/{orderby}','WhiteController@topsSearch');
//    Route::get('/White/SetEdit/{uid}','WhiteController@setwhite');
//    Route::get('/White/Search/{uid}','WhiteController@search');//查询
//    Route::get('/White/Set/Add',function (){
//        return view('White.add');
//    });//分数设置
//    Route::post('/White/SetSave','WhiteController@save');//保存新增分数
//    Route::post('/White/SetEdit','WhiteController@edit');//保存修改分数

    //我的信息
    Route::get('/MyInfo','MyInfoController@index');
    Route::post('/MyInfo/Save','MyInfoController@save');
    //活动公告
    Route::get('/Notice','MyInfoController@notice');
    //购买房卡
    Route::get('/BuyBubble/buy/{sid}','BuyBubbleController@buycard');
    Route::post('/BuyBubble/SetCard/{orderno}','BuyBubbleController@setCard');
    Route::post('/BuyBubble/del','BuyBubbleController@delOrderNo');
    Route::get('/BuyBubble/index','BuyBubbleController@index');
//    Route::get('/BuyBubble/index','CashController@index');
//    Route::get('/BuyBubble/buy/{number}','CashController@GetOrder');
//    Route::post('/BuyBubble/SetCard/{orderno}','CashController@setCard');

    //订单查询
    Route::get('/BuySearch',"CashController@search");
    Route::post('/BuySearch/data',"CashController@getData");

    //返利查询
    Route::get('/BackCash','BackCashController@index');
    Route::post('/BackCash/data','BackCashController@getData');
    //提现
    Route::get('/Extract','BackCashController@extract');
    //初次登录设置密码
    Route::get('/Extract/first',function (){
        return view('CashBuy.first');
    });
    Route::post('/Extract/first/save','BackCashController@savepwd');
    //输入提现密码，登录
    Route::get('/Extract/login',function (){
        return view('CashBuy.login');
    });
    Route::post('/Extract/login/submit','BackCashController@login');
    Route::get('/Extract/index','BackCashController@take');
    Route::post('/Extract/ext/{gold}','BackCashController@ext');
    Route::get('/Extract/extlist',function (){
        return view('CashBuy.ext_list');
    });
    Route::post('/BackCash/getlist','BackCashController@extlist');

    //黑名单
    Route::get('/Blacklist',function (){
        return view('Black.index');
    });
    Route::get('/Blacklist/add',function (){
        return view('Black.create');
    });
    Route::post('/Blacklist/data','BlacklistController@getData');
    Route::post('/Blacklist/save','BlacklistController@save');
    Route::post('/Blacklist/del/{id}','BlacklistController@Unlock');

    //删除代理
    Route::get('/AgentDel',function (){
        return view('AgentManage.delete');
    });
    Route::post('/AgentDel/data','AgentManageController@getAgent');
    Route::post('/AgentDel/delete','AgentManageController@delete');
    //代理审核
    Route::get('/Examine',function (){
        return view('AgentManage.examine');
    });
    Route::post('/Examine/data','AgentManageController@getData');
    Route::post('/Examine/adopt','AgentManageController@adopt');

    //玩家列表
    Route::get('/Players',function (){
        return view('UserManage.index');
    });
    Route::post('/Players/data','UserManageController@getData');
//    //微信订单查询
//    Route::get('/OrderSearch','CashController@WeChatOrder');
//    Route::get('/OrderSearch/select','CashController@WcoSarch');
});

//Route::get('/AddGroup/{gid}','GroupController@addGroup');//扫描二维码加群

//代理申请
Route::get('/Apply',function (){
   return view('AgentManage.apply');
});
Route::post('/Apply/save','AgentManageController@save');


//获取参数值
Route::get('/GetParam/{key}',function ($key){
    return App\Common\CommClass::GetParameter($key);
});

//玩家购买
Route::get('/PlayerBuy/buy/{sid}/{gameid}','CashController@buycard');
Route::post('/PlayerBuy/SetCard/{orderno}','CashController@setCard');
Route::get('/PlayerBuy/index','CashController@index');
Route::get('/PlayerBuy/list','CashController@buylist');


///////游戏api////////////////////////
Route::get("/Invitation/{uid}/{code}","GameSericeController@Invitation");//添加邀请码
Route::get("/GetMyPlayer/{uid}","GameSericeController@GetMyPlayer");//获取绑定我的玩家

Route::get("/Consume/{uid}","GameSericeController@Consume");//消费记录
Route::get("/Recharge/{uid}","GameSericeController@Recharge");//充值记录

Route::get("/Win/{uid}","GameSericeController@win");//胜率

Route::get("/give/{uid}","GameSericeController@give");//分享送钻

Route::get('/share/{roomNo?}/{msg?}','GameSericeController@index');
//Route::get('/mw/{roomNo?}/{msg?}','ShareController@index');

Route::get('/V/{v}/{t}/{res?}/{src?}',function ($v,$t,$res=null,$src=null){
    $ret = "";

        if($t==1) { //苹果版
            if ($v === "3.0") {
                $ret = "2";
            } else {
                if($v < 1.0){
                    $ret = "1";
                }else{
                    $ret = "0";
                }
            }
        }else if($t==2){
            if($v==0.5) {
                $ret = "2";
            }else if($v < 1.0){
                $ret = "1";
            }else{
                $ret = "0";
            }
        }else{ }
        if($ret=="0"){
            $ret .= "|1|1|1";
        }
    return $ret;
});
//总战绩
Route::get('/record/{uid}','GameSericeController@GetRecord');
//单局战绩
Route::get('/bigrecord/{roomid}/{time}','GameSericeController@BigRecord');
//玩家信息
Route::get('/player/{uid}','GameSericeController@GetPlayer');
//回放数据
Route::get('/Playback/{rid}','GameSericeController@getPlayback');

Route::get('/GetUrl',function (){
    return ['android'=>'http://wyhq.oss-cn-beijing.aliyuncs.com/android/wyhq.apk',
        'ios'=>'http://fir.im/4zn2',
        'resources'=>'http://cspp-collection.oss-cn-shenzhen.aliyuncs.com/update_package/',
        'files'=>'AB,resources.ab,image.ab,lua.ab'] ;
});
/// end//////////////////////////////