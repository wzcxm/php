<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Wechat\example\JsApiPay;

class UserLoginController extends Controller
{
    //
     /**
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public  function Login(Request $request){
        $user = \DB::table('v_user')->where([['uid',$request['id']],['pwd',$request['pwd']]])->first();
        if(empty($user)) {
            return redirect()->back()->with('idmsg','用户ID或者密码错误！');
        }
        else {
            if (!empty($user->rid) && $user->rid != 5  && $user->rid != 0 && $user->freeze != 1 && $user->ustate == 0) {
                Session::put('uid', $user->uid);
                Session::put('openid', $user->wxopenid);
                Session::put('front_uid', $user->front_uid);
                Session::put('roleid', $user->rid);
                Session::put('headimg', $user->head_img_url);
                Session::put('nick', $user->nickname);
                Session::put('roomcard', $user->roomcard);
                Session::put('rolename', $user->rname);
                Session::put('money', $user->money);
                Session::put('gold', $user->gold);
                return redirect('/Home');
            }else{
                return redirect('/Warning');
            }
        }
    }


    public function index(){
        try {
            CommClass::SetDataCache();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') === false) {
                return '<h1>请在微信客户端打开链接</h1>';
            } else {
                $tools = new JsApiPay();
                $openid = $tools->GetOpenid();
                $unionid = $tools->data['unionid'];
                $user = DB::table('v_user')->where('unionid', $unionid)->first();
                if (!empty($user)) {
                    if (!empty($user->rid) && $user->rid != 5  && $user->rid != 0 && $user->freeze != 1 && $user->ustate == 0) {
                        Session::put('uid', $user->uid);
                        Session::put('openid', $openid);
                        Session::put('front_uid', $user->front_uid);
                        Session::put('roleid', $user->rid);
                        Session::put('headimg', $user->head_img_url);
                        Session::put('nick', $user->nickname);
                        Session::put('roomcard', $user->roomcard);
                        Session::put('rolename', $user->rname);
                        Session::put('money', $user->money);
                        Session::put('gold', $user->gold);
                        return redirect('/Home');
                    } else {
                        return redirect('/Warning');
                    }
                } else {
                    return redirect('/Warning');
                }
            }
        }catch (\Exception $e){
            return view('errors.404');
        }
    }


    public function phoneLogin(Request $request){
        try{
            $tel = isset($request["tel"])?$request["tel"]:"";
            $code = isset($request["code"])?$request["code"]:"";
            if(empty($tel)){
                 return response()->json(['Error'=>'请输入手机号！']);
            }
            if(empty($code)){
                return response()->json(['Error'=>'请输入验证码！']);
            }
            //验证验证码
            $oldcode = \Cache::get($tel);
            if(empty($oldcode) || $oldcode!=$code){
                return response()->json(['Error'=>'验证码错误或已失效,请重新获取！']);
            }
            $user = \DB::table('v_user')->where('uphone',$tel)->first();
            if(empty($user)) {
                return response()->json(['Error'=>'该手机号码未绑定游戏！']);
            }
            else {
                if (!empty($user->rid) && $user->rid != 5  && $user->rid != 0 && $user->freeze != 1 && $user->ustate == 0) {
                    Session::put('uid', $user->uid);
                    Session::put('openid', $user->wxopenid);
                    Session::put('front_uid', $user->front_uid);
                    Session::put('roleid', $user->rid);
                    Session::put('headimg', $user->head_img_url);
                    Session::put('nick', $user->nickname);
                    Session::put('roomcard', $user->roomcard);
                    Session::put('rolename', $user->rname);
                    Session::put('money', $user->money);
                    Session::put('gold', $user->gold);
                    return response()->json(['Error'=>'OK']);
                }else{
                    return response()->json(['Error'=>'NO_AGENT']);
                }
            }
        }catch (\Exception $e){
            return response()->json(['Error'=>'登录失败，请重试！']);
        }
    }

}
