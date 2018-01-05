<?php

namespace App\Http\Controllers;

use App\Common\CommClass;
use App\Models\GroupInfo;
use App\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Users;
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
                Session::put('openid', $user->openid);
                Session::put('front_uid', $user->front_uid);
                Session::put('chief_uid', $user->chief_uid);
                Session::put('roleid', $user->rid);
                Session::put('headimg', $user->head_img_url);
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

    public function syslist(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            return view('UserLogin.Login');
        } else {
            $tools = new JsApiPay();
            $openid = $tools->GetOpenid();
            $unionid = $tools->data['unionid'];
            Session::put('unionid', $unionid);
            Session::put('openid', $openid);
            return view('UserLogin.sysList',['openid'=>$openid,'unionid'=>$unionid]);
        }
    }
    public function index(){
        try {
            CommClass::SetDataCache();
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($user_agent, 'MicroMessenger') === false) {
                return view('UserLogin.Login');
            } else {
                $unionid = session('unionid');
                $openid = session('openid');
                if(empty($unionid)){
                    $tools = new JsApiPay();
                    $openid = $tools->GetOpenid();
                    $unionid = $tools->data['unionid'];
                }
                $user = Users::where('unionid', $unionid)->first();
                if (!empty($user)) {
                    if (!empty($user->rid) && $user->rid != 5  && $user->rid != 0 && $user->freeze != 1 && $user->ustate == 0) {
                        Session::put('uid', $user->uid);
                        Session::put('openid', $openid);
                        Session::put('front_uid', $user->front_uid);
                        Session::put('chief_uid', $user->chief_uid);
                        Session::put('roleid', $user->rid);
                        Session::put('headimg', $user->head_img_url);
                        Session::put('roomcard', $user->roomcard);
                        Session::put('rolename', $this->getRolename($user->rid));
                        Session::put('backgold', $user->backgold);
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

    private function getRolename($roleid){
        $role = Role::find($roleid);
        return $role->rname;
    }
}
