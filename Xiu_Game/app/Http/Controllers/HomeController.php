<?php

namespace App\Http\Controllers;
use App\Common\CommClass;
use App\Models\BackGold;
use App\Wechat\example\JsApiPay;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Wechat\example\log;
use App\Wechat\example\CLogFileHandler;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    //
    public function index(){
        //用户信息
        $user = DB::table('view_user')->where('uid',session('uid'))->first();
        //注册用户
        $count_person=CommClass::GetPerosn();
        //当日登录
        $today_person = CommClass::GetOnlinePerosn();

        //当月积分
        $start = date('Y-m-01');
        $end =  date('Y-m-t 23:59:59', strtotime($start));
        $month = BackGold::where('get_id',session('uid'))->whereBetween('create_time', [$start, $end])->sum('backgold');
        //我的积分
        $total_num = BackGold::where('get_id',session('uid'))->sum('backgold');
        //菜单
        $mymenus= $this->GetMyMenus(session('roleid'));

        if(session('roleid') == 3 || session('roleid') == 4 || session('roleid') == 6){
            $back_agent = CommClass::getProxyScale(session('uid'));
        }else{
            $back_agent = CommClass::GetParameter("upper_one");
        }
        $back_agent_front = CommClass::GetParameter("upper_two");

        //返回页面的数据
        $retView = [
            'User'=>$user,
            'roleid'=>session('roleid'),
            'count_person'=>$count_person,
            'today_person'=>$today_person,
            'total_num'=>$total_num,
            'month'=>$month,
            'back_agent'=>$back_agent,
            'back_agent_front'=>$back_agent_front,
            'Menus'=>$mymenus
        ];
            return view('Home.index',$retView);
    }

    function  GetMyMenus($roleid){
         $Menus = Cache::rememberForever('RM', function () {
             return DB::table('role_menu')->get();
         });
         $ret_menus = $Menus->where('roleid',$roleid);
         if($roleid == 2 || $roleid == 3 || $roleid == 6){
             $isGift = DB::table('xx_sys_isgift')->where('uid',session('uid'))->first();
             if(!empty($isGift)){
                 return $ret_menus;
             }else{
                 return $ret_menus->where('menuid','<>',10);
             }
         }else{
             return $ret_menus;
         }
    }

    public function  updatePhone(Request $request){
        try{
            $tel = isset($request['tel'])?$request['tel']:0;
            $code = isset($request['code'])?$request['code']:0;
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
            if(!$this->is_mobile($tel)){
                return response()->json(['Error'=>"请输入合法的手机号!"]);
            }
            $user_tel = DB::table('xx_user')->where("uphone",$tel)->get();
            if(count($user_tel) == 0){
                DB::table('xx_user')->where('uid',session('uid'))->update(['uphone'=>$tel]);
                return response()->json(['Error'=>""]);
            }else{
                return response()->json(['Error'=>"该手机号已绑定，不能重复绑定!"]);
            }
        }catch (\Exception $e){
            return response()->json(['Error'=>"绑定失败！"]);
        }
    }

    //验证手机号是否合法
    private function is_mobile($phone) {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if ( preg_match( $search, $phone ) ) {
            return  true ;
        } else {
            return  false ;
        }
    }


    public function updateWx(){
        try{
            $arr = ['old_head'=>session('headimg'),'old_nick'=>session('nick')];
            $tools = new JsApiPay();
            $data = $tools->GetUserInfo();
            if(!empty($data)){
                Session::put('NewWxData', $data);
                $arr['new_head'] = substr($data['headimgurl'],0,strrpos($data['headimgurl'],'/')).'/64';
                $arr['new_nick'] = addslashes($data['nickname']);
            }
            return view('Home.updatewx',$arr);
        }catch (\Exception $e){
            //var_dump($e->getMessage());
            //return response()->json(['status'=>0,'message'=>$e->getMessage()]);
        }
    }

    public function replace(Request $request){
        try{
            $data = session('NewWxData');
            if(!empty($data)){
                //删除该微信已有的账号
                $old_play = DB::table('xx_user')->where('unionid',$data['unionid'])->first();
                if(!empty($old_play) && $old_play->uid != session('uid')){
                    DB::select('CALL delete_uid('.$old_play->uid.')');
                }
                //微信信息绑定到游戏账号
                DB::table('xx_user')->where('uid',session('uid'))->update([
                    'nickname'=>addslashes($data['nickname']),
                    'head_img_url'=>substr($data['headimgurl'],0,strrpos($data['headimgurl'],'/')).'/64',
                    'sex'=>$data['sex'],
                    'unionid'=>$data['unionid'],
                    'wxopenid'=>$data['openid']
                ]);
                return response()->json(['error'=>'']);
            }else{
                return response()->json(['error'=>'更新失败，请刷新重试！']);
            }
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

}
